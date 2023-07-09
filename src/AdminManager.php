<?php

namespace Bengr\Admin;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\GlobalActions\GlobalAction;
use Bengr\Admin\GlobalSearch\GlobalSearchProvider;
use Bengr\Admin\Navigation;
use Bengr\Admin\Navigation\UserMenuItem;
use Bengr\Admin\Pages\Page;
use Bengr\Auth\Exceptions\AlreadyAuthenticatedException;
use Bengr\Support\Url\UrlResolver;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class AdminManager
{
    protected array $pages = [];

    protected ?Page $currentPage = null;

    protected array $globalActions = [];

    protected array $navigationGroups = [];

    protected array $navigationItems = [];

    protected bool $isNavigationMounted = false;

    protected array $userMenuItems = [];

    protected string $globalSearchProvider = GlobalSearchProvider::class;

    public function auth(): Guard
    {
        return auth()->guard($this->getGuardName());
    }

    public function serving(\Closure $callback): void
    {
        Event::listen(ServingAdmin::class, $callback);
    }

    public function getLoginPage(): ?Page
    {
        return $this->getPageByKey('login');
    }

    public function getDashboardPage(): ?Page
    {
        return $this->getPageByKey('dashboard');
    }

    public function getApiPrefix(): ?string
    {
        return config('admin.api.prefix') ?? null;
    }

    public function getApiMiddleware(): null | string | array
    {
        return config('admin.api.middleware') ?? [];
    }

    public function getApiRoutes(): ?array
    {
        return config('admin.api.routes') ?? [];
    }

    public function getApiRouteUrl(string $name): ?string
    {
        $route = config('admin.api.routes')[$name];

        if (!$route) return null;

        return config('admin.api.prefix') . $route['url'];
    }

    public function getGuardName(): string
    {
        return config('admin.auth.guard');
    }

    public function getAuthUserModel(): string
    {
        return $this->auth()->getProvider()->getModel();
    }

    public function getAuthTokenModel(): string
    {
        return config('auth.tokens')[config('auth.guards')[config('admin.auth.guard')]['provider']]['model'];
    }

    public function setCurrentPage(?Page $page): self
    {
        $this->currentPage = $page;

        return $this;
    }

    public function registerPages(array $pages): void
    {
        $this->pages = array_merge($this->pages, $pages);
    }

    public function registerGlobalActions(array $globalActions): void
    {
        $this->globalActions = array_merge($this->globalActions, $globalActions);
    }

    public function registerNavigationItems(array $items): void
    {
        $this->navigationItems = array_merge($this->navigationItems, $items);
    }

    public function mountNavigation(): void
    {
        foreach ($this->getPages() as $page) {
            app($page)->registerNavigationItems();
        }

        $this->isNavigationMounted = true;
    }

    public function registerUserMenuItems(array $items): void
    {
        $this->userMenuItems = array_merge($this->userMenuItems, $items);
    }

    public function registerHandler(Handler $handler): void
    {
        $handler->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is($this->getApiPrefix() . '/*')) {
                $login = $this->getPageByKey('login');

                return response()->json([
                    'message' => 'Not logged in',
                    'redirect' => $login ? [
                        'url' => $login->getRouteUrl(),
                        'name' => $login->getRouteName()
                    ] : null
                ], 401);
            }
        });

        $handler->renderable(function (AlreadyAuthenticatedException $e, $request) {
            if ($request->is($this->getApiPrefix() . '/*')) {
                $dashboard = $this->getPageByKey('dashboard');

                return response()->json([
                    'message' => 'already logged in',
                    'redirect' => $dashboard ? [
                        'url' => $dashboard->getRouteUrl(),
                        'name' => $dashboard->getRouteName()
                    ] : null
                ], 403);
            }
        });
    }

    public function getPages(): array
    {
        return array_unique($this->pages);
    }

    public function getPageByKey(string $key): ?Page
    {
        if (array_key_exists($key, config('admin.components.pages.register'))) {
            return app(config('admin.components.pages.register')[$key]);
        } else {
            return null;
        }
    }

    public function getPageByUrl($url): ?Page
    {
        $resolver = new UrlResolver($url, collect($this->getPages())->map(fn ($page) => app($page)->getRouteUrl())->values()->toArray());
        $resolvedUrl = $resolver->resolve();

        if (!$resolvedUrl) return null;

        $pageClassName = collect($this->getPages())->first(function ($page) use ($resolvedUrl) {
            return $resolvedUrl->getOriginalUrl() === app($page)->getSlug();
        });

        if (!$pageClassName) return null;

        try {
            $page = $resolvedUrl->substituteImplicitBindings(app($pageClassName));
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $page->bindParameters($resolvedUrl);
        $this->setCurrentPage($page);

        return $page;
    }

    public function getCurrentPage(): ?Page
    {
        return $this->currentPage;
    }

    public function getGlobalActions(): array
    {
        return array_unique($this->globalActions);
    }

    public function getGlobalActionByName(string $name)
    {
        $globalAction = collect($this->getGlobalActions())->first(function ($globalAction) use ($name) {
            return app($globalAction)->getName() === $name;
        });
        if (!$globalAction) return null;

        return app($globalAction);
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

    public function getUserMenuItems(): Collection
    {
        return collect($this->userMenuItems)
            ->sortBy(fn (UserMenuItem $item): int => $item->getSort())
            ->values();
    }

    public function getGlobalSearchProvider(): GlobalSearchProvider
    {
        return app($this->globalSearchProvider);
    }


    public function registerComponents(): void
    {
        $this->pages = config('admin.components.pages.register') ?? [];
        $this->globalActions = config('admin.components.global_actions.register') ?? [];

        $this->registerComponentsFromDirectory(
            Page::class,
            $this->pages,
            config('admin.components.pages.path'),
            config('admin.components.pages.namespace'),
        );
        $this->registerComponentsFromDirectory(
            GlobalAction::class,
            $this->globalActions,
            config('admin.components.global_actions.path'),
            config('admin.components.global_actions.namespace'),
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
