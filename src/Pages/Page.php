<?php

namespace Bengr\Admin\Pages;

use App\Http\Kernel;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Navigation\NavigationItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Page
{
    protected ?string $layout = 'app';

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $slug = null;

    protected ?string $parent = null;

    protected string | array $middlewares = [];

    protected ?string $navigationLabel = null;

    protected ?string $navigationIcon = null;

    protected ?string $navigationActiveIcon = null;

    protected ?string $navigationGroup = null;

    protected ?int $navigationSort = null;

    protected bool $inNavigation = true;

    protected bool $hasNavigation = true;

    protected bool $hasTopbar = true;

    protected array $breadcrumbs = [];

    protected ?string $model = null;

    public function registerNavigationItems(): void
    {
        if (!$this->inNavigation()) {
            return;
        }

        BengrAdmin::registerNavigationItems($this->getParent() ? [] : $this->getNavigationItems());
    }

    public function getNavigationItems(): array
    {

        if (!$this->inNavigation()) {
            return [];
        }

        return [
            NavigationItem::make($this->getNavigationLabel())
                ->group($this->getNavigationGroup())
                ->icon($this->getNavigationIcon())
                ->activeIcon($this->getNavigationActiveIcon())
                ->sort($this->getNavigationSort())
                ->badge($this->getNavigationBadge(), $this->getNavigationBadgeColor())
                ->children($this->getChildren())
                ->route($this->getRouteName(), $this->getRouteUrl())
        ];
    }

    public function getChildren(): array
    {
        return collect(BengrAdmin::getPages())->reject(function ($item) {
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
        $slug = $this->getSlug();

        return "admin.pages.{$slug}";
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
                return ["name" => app($item)->getTitle(), "url" => app($item)->getRouteUrl()];
            })
            ->mapWithKeys(function ($item) {
                return [$item["url"] => $item["name"]];
            })
            ->toArray();

        return $this->breadcrumbs;
    }

    protected function getNavigationLabel(): ?string
    {
        return $this->navigationLabel ?? $this->getTitle();
    }

    protected function getNavigationIcon(): ?string
    {
        return $this->navigationIcon ?? 'description';
    }

    protected function getNavigationActiveIcon(): ?string
    {
        return $this->navigationActiveIcon ?? $this->getNavigationIcon();
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

    public function hasTable(): bool
    {
        return method_exists(static::class, 'getTable');
    }

    public function processMiddleware(int $index, Request $request, \Closure $response)
    {
        $middleware = $this->middlewares[$index];
        $data = [];

        if (!class_exists($this->middlewares[$index])) {
            $parsed = explode(':', $middleware);
            $middleware = array_key_exists($parsed[0], app(Kernel::class)->getRouteMiddleware()) ? app(Kernel::class)->getRouteMiddleware()[$parsed[0]] : null;
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
        if (!count($this->middlewares)) return;

        return $this->processMiddleware(0, $request, $response);
    }

    public function inNavigation(): bool
    {
        return $this->inNavigation;
    }

    public function hasNavigation(): bool
    {
        return $this->hasNavigation;
    }

    public function hasTopbar(): bool
    {
        return $this->hasTopbar;
    }
}
