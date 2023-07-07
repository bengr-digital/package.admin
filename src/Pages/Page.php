<?php

namespace Bengr\Admin\Pages;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Actions\ActionGroup;
use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\GlobalSearch\GlobalSearchResult;
use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Navigation\NavigationItem;
use Bengr\Admin\Widgets\FormWidget;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Bengr\Support\response;

class Page
{
    protected ?string $layout = 'app';

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $slug = null;

    protected ?string $parent = null;

    protected string | array $middlewares = [];

    protected ?string $navigationLabel = null;

    protected ?string $navigationIconName = null;

    protected ?string $navigationIconType = null;

    protected ?string $navigationActiveIconName = null;

    protected ?string $navigationActiveIconType = null;

    protected ?string $navigationGroup = null;

    protected ?int $navigationSort = null;

    protected bool $inNavigation = true;

    protected bool $hasNavigation = true;

    protected bool $hasTopbar = true;

    protected bool $hasLargeForm = false;

    protected array $breadcrumbs = [];

    protected ?string $model = null;

    protected array $params = [];

    protected $transformed_widgets;

    protected $transformed_actions;

    protected bool $isGloballySearchable = false;

    protected int $globalSearchResultsLimit = 5;

    public function slug(string $slug): self
    {
        $this->slug = Str::of($slug)->explode('/')->filter()->join('/');

        return $this;
    }

    public function params(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    public function registerNavigationItems(): void
    {
        if (!$this->inNavigation()) {
            return;
        }

        Admin::registerNavigationItems($this->getParent() ? [] : $this->getNavigationItems());
    }

    public function getNavigationItems(): array
    {

        if (!$this->inNavigation()) {
            return [];
        }

        return [
            NavigationItem::make($this->getNavigationLabel())
                ->group($this->getNavigationGroup())
                ->icon($this->getNavigationIconName(), $this->getNavigationIconType())
                ->activeIcon($this->getNavigationActiveIconName(), $this->getNavigationActiveIconType())
                ->sort($this->getNavigationSort())
                ->badge($this->getNavigationBadge(), $this->getNavigationBadgeColor())
                ->route($this->getRouteName(), $this->getRouteUrl())
                ->children($this->getChildren())
        ];
    }

    public function getChildren(): array
    {
        return collect(Admin::getPages())->reject(function ($item) {
            return app($item)->getParent() !== $this::class;
        })->toArray();
    }

    public function getWidgets(): array
    {
        return [];
    }

    public function getActions(): array
    {
        return [];
    }

    public function getModals(): array
    {
        return [];
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function getTitle(): string
    {
        return $this->title ?? (string) Str::of(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    public function getModel(): ?Model
    {
        return $this->model ? app($this->model) : null;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSlug(): string
    {
        return $this->slug ?? Str::of($this->title ?? class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getMiddlewares(): string | array
    {
        return $this->middlewares;
    }

    public function getRouteName(): string
    {
        $slug = $this->getSlug() === "" ? "index" : $this->getSlug();

        $slug = str_replace('/', '.', $slug);

        return "admin.components.pages.{$slug}";
    }

    public function getRouteUrl(): string
    {
        $slug = $this->getSlug();

        return "/{$slug}";
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function getBreadcrumb(Page $page): void
    {
        if ($page->getParent()) {
            $this->breadcrumbs[] = $page::class;
            $this->getBreadcrumb(app($page->getParent()));
        } else {
            $this->breadcrumbs[] = $page::class;
        }
    }

    public function getBreadcrumbs(): array
    {
        if ($this->getParent()) {
            $this->getBreadcrumb($this);
        }

        $this->breadcrumbs = collect($this->breadcrumbs)
            ->reverse()
            ->map(function ($item) {
                return ["name" => app($item)->getTitle(), "route" => [
                    'name' => app($item)->getRouteName(),
                    'url' => app($item)->getRouteUrl(),
                ]];
            })->values()->toArray();

        if (count($this->breadcrumbs)) {
            $this->breadcrumbs[count($this->breadcrumbs) - 1]['route'] = null;
        }

        return $this->breadcrumbs;
    }

    protected function getNavigationLabel(): ?string
    {
        return $this->navigationLabel ?? $this->getTitle();
    }

    protected function getNavigationIconName(): ?string
    {
        return $this->navigationIconName ?? 'description';
    }

    protected function getNavigationIconType(): ?string
    {
        return $this->navigationIconType ?? 'outlined';
    }

    protected function getNavigationActiveIconName(): ?string
    {
        return $this->navigationActiveIconName ?? $this->getNavigationIconName();
    }

    protected function getNavigationActiveIconType(): ?string
    {
        return $this->navigationActiveIconType ?? $this->getNavigationIconType();
    }

    protected function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    protected function getNavigationSort(): ?int
    {
        return $this->navigationSort;
    }

    protected function getNavigationBadge(): ?string
    {
        return null;
    }

    protected function getNavigationBadgeColor(): ?string
    {
        return null;
    }

    public function processMiddleware(int $index, Request $request, \Closure $response)
    {
        $middleware = $this->middlewares[$index];
        $data = [];

        if (!class_exists($this->middlewares[$index])) {
            $parsed = explode(':', $middleware);
            $middleware = array_key_exists($parsed[0], Admin::getHttpKernel()->getRouteMiddleware()) ? Admin::getHttpKernel()->getRouteMiddleware()[$parsed[0]] : null;
            $data = array_splice($parsed, 1);
        }
        if (!$middleware) return $response();

        if ($index === count($this->middlewares) - 1) {
            return app($middleware)->handle($request, $response, ...$data);
        } else {
            return app($middleware)->handle($request, fn () => $this->processMiddleware($index + 1, $request, $response), ...$data);
        }
    }

    public function processToResponse(Request $request, \Closure $response)
    {
        if (!count($this->middlewares)) return $response();

        return $this->processMiddleware(0, $request, $response);
    }

    public function inNavigation(): bool
    {
        return $this->inNavigation;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    protected function loopWidgets(array $widgets)
    {
        collect($widgets)->map(function ($widget) {
            $this->transformed_widgets->push($widget);

            if ($widget->hasWidgets()) {
                $this->loopWidgets($widget->getWidgets());
            }
        });
    }

    public function hasWidget(?int $id): bool
    {
        return $this->getWidget($id) ? true : false;
    }

    public function getFlatWidgets(?array $widgets = null): array
    {
        $flatten = [];
        $widgets = $widgets ?? $this->getTransformedWidgets();

        foreach ($widgets as $widget) {
            $flatten[] = $widget;

            if ($widget->hasWidgets()) {
                $flatten = array_merge($flatten, $this->getFlatWidgets($widget->getWidgets()));
            }
        }

        return $flatten;
    }

    public function getWidget(?int $id): ?Widget
    {
        $this->transformed_widgets = collect([]);
        $widgets_to_transform = $this->getTransformedWidgets();

        foreach ($this->getTransformedModals() as $modal) {
            $modal->params(request()->get('params') ?? null);
            array_push($widgets_to_transform, ...$modal->getTransformedWidgets());
        }

        $this->loopWidgets($widgets_to_transform);

        $widget = $this->transformed_widgets->where(function (Widget $widget) use ($id) {
            return $widget->getWidgetId() === $id;
        })->toArray();

        return array_shift($widget);
    }

    public function getModal(?int $id): ?Modal
    {
        return collect($this->getTransformedModals())->first(fn (Modal $modal) => $modal->getId() == $id);
    }

    public function getLargeForm(): ?FormWidget
    {
        if (collect($this->getTransformedWidgets())->first() instanceof FormWidget && $this->hasLargeForm()) {
            collect($this->getTransformedWidgets())->first()->showOnlyChildren(true);
            return collect($this->getTransformedWidgets())->first()->withoutProps();
        }

        return null;
    }

    public function hasNavigation(): bool
    {
        return $this->hasNavigation;
    }

    public function hasTopbar(): bool
    {
        return $this->hasTopbar;
    }

    public function hasLargeForm(): bool
    {
        return $this->hasLargeForm == true ? $this->hasLargeForm : collect($this->getTransformedActions())->contains(function ($action) {
            if ($action->getName() == 'submit' && $action->hasHandle()) {
                return collect($this->getFlatWidgets($this->getTransformedWidgets()))->contains(fn ($widget) => $widget->getWidgetId() == $action->getHandleWidgetId() && $widget instanceof FormWidget);
            }
        });
    }

    protected function loopActions(array $actions)
    {
        collect($actions)->map(function ($action) {
            if ($action instanceof ActionGroup) {
                $this->loopActions($action->getActions());
            } else {
                $this->transformed_actions->push($action);
            }
        });
    }

    public function callAction(string $name, array $payload = [])
    {
        $this->transformed_actions = collect([]);

        $this->loopActions($this->getActions());

        $action = $this->transformed_actions->where(function (Action $action) use ($name) {
            return $action->getName() === $name && $action->hasHandle();
        })->first();

        if (!$action) return response()->throw(ActionNotFoundException::class);

        return $action->getHandleMethod()($payload);
    }

    public function getGlobalSearchCategoryLabel(): ?string
    {
        return $this->getTitle();
    }

    public function canGloballySearch(): bool
    {
        return count($this->getGlobalSearchAttributes());
    }

    public function getGlobalSearchResultsLimit(): int
    {
        return $this->globalSearchResultsLimit;
    }

    public function getGlobalSearchAttributes(): array
    {
        return [];
    }

    public function getGlobalSearchResult(Model $record): ?GlobalSearchResult
    {
        return null;
    }

    public function getGlobalSearchResults(string $searchQuery): Collection
    {
        if (!$this->getModel()) return collect([]);

        $query = $this->getModel()->query();

        $this->getGlobalSearchEloquentQuery($query);

        foreach (explode(' ', $searchQuery) as $searchQueryWord) {
            $query->where(function (Builder $query) use ($searchQueryWord) {
                $isFirst = true;

                foreach ($this->getGlobalSearchAttributes() as $attributes) {
                    $this->applyGlobalSearchAttributeConstraint($query, Arr::wrap($attributes), $searchQueryWord, $isFirst);
                }
            });
        }

        return $query
            ->limit($this->getGlobalSearchResultsLimit())
            ->get()
            ->map(function (Model $record): ?GlobalSearchResult {
                $result = $this->getGlobalSearchResult($record);

                if (!$result) return null;

                return $result;
            })
            ->filter();
    }

    protected function applyGlobalSearchAttributeConstraint(Builder $query, array $searchAttributes, string $searchQuery, bool &$isFirst): Builder
    {
        $searchQuery = strtolower($searchQuery);

        foreach ($searchAttributes as $searchAttribute) {
            $whereClause = $isFirst ? 'where' : 'orWhere';
            $query->when(
                Str::of($searchAttribute)->contains('.'),
                fn ($query) => $query->{"{$whereClause}Relation"}(
                    (string) Str::of($searchAttribute)->beforeLast('.'),
                    (string) Str::of($searchAttribute)->afterLast('.'),
                    'like',
                    "%{$searchQuery}%",
                ),
                fn ($query) => $query->{$whereClause}(
                    DB::raw("lower($searchAttribute)"),
                    'like',
                    "%{$searchQuery}%",
                ),
            );
            $isFirst = false;
        }

        return $query;
    }

    protected function getGlobalSearchEloquentQuery(Builder $query): Builder
    {
        return $query;
    }

    protected function response($content = ''): PageResponse
    {
        return PageResponse::make($content);
    }

    public function getWidgetsWithAutomaticIds(array $widgets, int $index): array
    {
        foreach ($widgets as $widget) {
            if (!$widget->getWidgetId()) {
                $widget->widgetId($index);
            }

            $index += 1;

            if ($widget->hasWidgets()) {
                $this->getWidgetsWithAutomaticIds($widget->getWidgets(), $index);
            }

            $index = $index + count($this->getFlatWidgets($widget->getWidgets()));
        }

        return $widgets;
    }

    public function getModalsWithAutomaticIds(array $modals): array
    {
        $id = count($this->getFlatWidgets()) + 1;

        foreach ($modals as $index => $modal) {
            if ($index > 0) {
                $id += count($modals[$index - 1]->getFlatWidgets()) + 1;
            }

            $modal->id($id);
        }

        return $modals;
    }

    public function getTransformedModals(): array
    {
        return $this->getModalsWithAutomaticIds($this->getModals());
    }

    public function getTransformedWidgets(): array
    {
        return $this->getWidgetsWithAutomaticIds($this->getWidgets(), 1);
    }

    public function getTransformedActions(): array
    {
        $actions = $this->getActions();

        foreach ($actions as $action) {
            if ($action->getName() == 'submit' && !$action->hasHandle()) {
                $action->handle(null, null);
                $action->type('submit');
                break;
            }

            if ($action->getModalCodeId() && !$action->getModalId()) {
                $modal = collect($this->getTransformedModals())->first(fn ($modal) => $modal->getCodeId() == $action->getModalCodeId());

                if ($modal) {
                    $action->modal($modal->getId(), $action->getModalEvent());
                }
            }
        }

        return $actions;
    }
}
