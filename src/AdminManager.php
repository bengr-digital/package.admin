<?php

namespace Bengr\Admin;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\Navigation;
use Bengr\Admin\Navigation\UserMenuItem;
use Bengr\Admin\Pages\Page;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class AdminManager
{
    protected bool $isNavigationMounted = false;

    protected array $navigationGroups = [];

    protected array $navigationItems = [];

    protected array $userMenuItems = [];

    protected array $pages = [];

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
        return config('admin.prefix');
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

    public function getPageByUrl($url)
    {
        if ($this->getPagesUrls()->contains($url)) {
            $page = collect($this->getPages())->first(function ($page) use ($url) {
                return app($page)->getRouteUrl() === $url;
            });

            return app($page);
        } else {
            $page = collect($this->getPages())->first(function ($page) use ($url) {
                return app($page)->getRouteUrl() === $this->getPagesUrls()->reject(function ($item) use ($url) {
                    $itemParts = collect(explode('/', $item))->reject(fn ($item) => empty($item));
                    $urlParts = collect(explode('/', $url))->reject(fn ($item) => empty($item));
                    $hasParams = $itemParts->map(fn ($item) => $this->isParam($item))->contains(true);

                    if ($itemParts->count() !== $urlParts->count()) return true;
                    if (!$hasParams && $item !== $url) return true;

                    if ($itemParts->map(function ($item, $index) use ($urlParts) {
                        if (($urlParts->get($index) === $item && !$this->isParam($index)) || $this->isParam($item)) {
                            return true;
                        } else {
                            return false;
                        }
                    })->contains(false)) return true;
                })->first();;
            });

            if (!$page || !app($page)->getModel()) {
                return null;
            }

            $params = collect();


            collect(explode('/', app($page)->getRouteUrl()))->reject(fn ($item) => empty($item))->each(function ($item, $index) use ($url, $params) {
                if ($this->isParam($item)) {
                    $key = Str::of($item)->after('{')->before('}');
                    $params->put($key->value(), collect(explode('/', $url))->reject(fn ($item) => empty($item))->get($index));
                }
            });


            $model = app($page)->getModel()->where(function ($query) use ($params) {
                $params->each(function ($value, $key) use ($query) {
                    $query->where($key, $value);
                });
            })->first();

            if ($model) return app($page);
            else return null;
        }
    }

    public function getPageByName($name)
    {
        $page = collect($this->getPages())->first(function ($page) use ($name) {
            return app($page)->getRouteName() === $name;
        });

        if (!$page) return null;

        return app($page);
    }

    public function getUserMenuItems(): Collection
    {
        return collect($this->userMenuItems)
            ->sort(fn (UserMenuItem $item): int => $item->getSort());
    }
}
