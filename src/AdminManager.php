<?php

namespace Bengr\Admin;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\GlobalSearch\GlobalSearchProvider;
use Bengr\Admin\Navigation;
use Bengr\Admin\Navigation\UserMenuItem;
use Bengr\Admin\Pages\Page;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class AdminManager
{
    protected bool $isNavigationMounted = false;

    protected string $globalSearchProvider = GlobalSearchProvider::class;

    protected array $navigationGroups = [];

    protected array $navigationItems = [];

    protected array $userMenuItems = [];

    protected array $pages = [];

    protected array $globalActions = [];

    protected ?Page $currentPage = null;

    public function auth(): Guard
    {
        return auth()->guard($this->getGuardName());
    }

    public function getGuardName(): string
    {
        return config('admin.auth.guard');
    }

    public function authUserModel(): string
    {
        return $this->auth()->getProvider()->getModel();
    }

    public function prefix(): string
    {
        return Str::of(config('admin.routes.url'))->ltrim('/')->value();
    }

    public function loginPage(): ?Page
    {
        return config('admin.pages.login') ? app(config('admin.pages.login')) : null;
    }

    public function dashboardPage(): ?Page
    {
        return config('admin.pages.dashboard') ? app(config('admin.pages.dashboard')) : null;
    }

    public function authTokenModel(): string
    {
        return config('auth.tokens')[config('auth.guards')[config('admin.auth.guard')]['provider']]['model'];
    }

    public function mountNavigation(): void
    {
        foreach ($this->getPages() as $page) {
            app($page)->registerNavigationItems();
        }
    }

    public function registerNavigationItems(array $items): void
    {
        $this->navigationItems = array_merge($this->navigationItems, $items);
    }

    public function registerPages(array $pages): void
    {
        $this->pages = array_merge($this->pages, $pages);
    }

    public function registerGlobalActions(array $globalActions): void
    {
        $this->globalActions = array_merge($this->globalActions, $globalActions);
    }

    public function registerUserMenuItems(array $items): void
    {
        $this->userMenuItems = array_merge($this->userMenuItems, $items);
    }

    public function serving(\Closure $callback): void
    {
        Event::listen(ServingAdmin::class, $callback);
    }

    public function getNavigation(): Collection
    {
        if (!$this->isNavigationMounted) {
            $this->mountNavigation();
        }

        return collect($this->getNavigationItems())
            ->sortBy(fn (Navigation\NavigationItem $item): int => $item->getSort())
            ->groupBy(fn (Navigation\NavigationItem $item): ?string => $item->getGroup())
            ->map(function (Collection $items, ?string $groupIndex): Navigation\NavigationGroup {

                if (blank($groupIndex)) {
                    return Navigation\NavigationGroup::make()->items($items);
                }

                return Navigation\NavigationGroup::make($groupIndex)->items($items);
            })->values();
    }

    public function getNavigationGroups(): array
    {
        return $this->navigationGroups;
    }

    public function getNavigationItems(): array
    {
        return $this->navigationItems;
    }

    public function getPages(): array
    {
        return array_unique($this->pages);
    }

    public function getGlobalActions(): array
    {
        return array_unique($this->globalActions);
    }

    public function getPagesUrls(): Collection
    {
        return collect($this->getPages())->map(function ($page) {
            return app($page)->getRouteUrl();
        });
    }

    public function isParam($value): bool
    {
        return Str::of($value)->startsWith('{') && Str::of($value)->endsWith('}');
    }

    public function urlHasParams($url)
    {
        return Str::of($url)
            ->explode("/")
            ->filter()
            ->map(function ($part) {
                return $this->isParam($part);
            })
            ->contains(true);
    }

    public function getPageByUrl($url)
    {
        $page = collect($this->getPages())->first(function ($page) use ($url) {
            if (!$this->urlHasParams(app($page)->getRouteUrl())) {
                return app($page)->getRouteUrl() === $url;
            }

            $page_url = $this->getPagesUrls()
                ->filter(function ($pageUrl) use ($url) {
                    return Str::of($pageUrl)->explode("/")->filter()->count() === Str::of($url)->explode("/")->filter()->count() && $this->urlHasParams($pageUrl);
                })
                ->filter(function ($pageUrl) use ($url) {
                    $url_parts = Str::of($url)->explode("/")->filter()->values();

                    if (Str::of($pageUrl)->explode("/")->filter()->values()->map(function ($part, $index) use ($url_parts) {
                        if ($part === $url_parts->get($index) && !$this->isParam($part)) {
                            return true;
                        }

                        if ($part !== $url_parts->get($index) && !$this->isParam($part)) {
                            return false;
                        }

                        return true;
                    })->contains(false)) return false;

                    return true;
                })->first();

            return $page_url === app($page)->getRouteUrl() ? true : false;
        });

        if (!$page) return null;

        if ($this->urlHasParams(app($page)->getRouteUrl())) {
            $page_url_parts = Str::of(app($page)->getRouteUrl())->explode("/")->filter()->values();
            $url_parts = Str::of($url)->explode("/")->filter()->values();

            $params = $page_url_parts->map(function ($part, $index) use ($url_parts, $page) {
                if ($this->isParam($part)) {
                    $table = null;
                    $column = null;
                    $value = $url_parts->get($index);

                    $parsed_param = Str::of($part)->replace('{', '')->replace('}', '')->explode(':');
                    if ($parsed_param->count() == 2) {
                        $table = $parsed_param[0];
                        $column = $parsed_param[1];
                    } else {
                        $table = app($page)->getModel() ? app($page)->getModel()->getTable() : null;
                        $column = $parsed_param[0];
                    }

                    return [
                        'table' => $table,
                        'column' => $column,
                        'value' => $value
                    ];;
                }
            })->filter()->values();

            $params = $params->map(function ($param, $index) use ($params) {

                $param['record'] = !$param['table'] ? null : DB::table($param['table'])->where($param['column'], $param['value']);


                return $param;
            });

            if ($params->first(function ($param) {
                $column = $param['column'];

                return $param['record'] === null || !$param['record']->exists() || $param['record']->first()->$column != $param['value'];
            })) return null;

            $page = app($page)->slug($url)->params($params->toArray());
            $this->currentPage = $page;

            return $page;
        }
        $page = app($page);
        $this->currentPage = $page;

        return $page;
    }

    public function getGlobalActionByName(string $name)
    {
        $globalAction = collect($this->getGlobalActions())->first(function ($globalAction) use ($name) {
            return app($globalAction)->getName() === $name;
        });
        if (!$globalAction) return null;

        return app($globalAction);
    }

    public function getUserMenuItems(): Collection
    {
        return collect($this->userMenuItems)
            ->sort(fn (UserMenuItem $item): int => $item->getSort());
    }

    public function getGlobalSearchProvider(): GlobalSearchProvider
    {
        return app($this->globalSearchProvider);
    }

    public function getCurrentPage(): ?Page
    {
        return $this->currentPage;
    }

    public function registerComponents(): void
    {
        $this->pages = config('admin.pages.register') ?? [];
        $this->globalActions = config('admin.global_actions.register') ?? [];

        $this->registerComponentsFromDirectory(
            Page::class,
            $this->pages,
            config('admin.pages.path'),
            config('admin.pages.namespace'),
        );
        $this->registerComponentsFromDirectory(
            GlobalAction::class,
            $this->globalActions,
            config('admin.global_actions.path'),
            config('admin.global_actions.namespace'),
        );
    }

    protected function registerComponentsFromDirectory(string $baseClass, array &$register, ?string $directory, ?string $namespace): void
    {
        if (blank($directory) || blank($namespace)) {
            return;
        }

        $filesystem = app(Filesystem::class);
        $files = [];

        if ($filesystem->exists($directory)) {
            $files = $filesystem->allFiles($directory);
        }

        $namespace = Str::of($namespace);

        $register = array_merge(
            $register,
            collect($files)
                ->map(function (SplFileInfo $file) use ($namespace): string {
                    $variableNamespace = $namespace->contains('*') ? str_ireplace(
                        ['\\' . $namespace->before('*'), $namespace->after('*')],
                        ['', ''],
                        Str::of($file->getPath())
                            ->after(base_path())
                            ->replace(['/'], ['\\']),
                    ) : null;

                    if (is_string($variableNamespace)) {
                        $variableNamespace = (string) Str::of($variableNamespace)->before('\\');
                    }

                    return (string) $namespace
                        ->append('\\', $file->getRelativePathname())
                        ->replace('*', $variableNamespace)
                        ->replace(['/', '.php'], ['\\', '']);
                })
                ->filter(fn (string $class): bool => is_subclass_of($class, $baseClass) && (!(new \ReflectionClass($class))->isAbstract()))
                ->all(),
        );
    }
}
