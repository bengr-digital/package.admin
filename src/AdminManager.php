<?php

namespace Bengr\Admin;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\Navigation;
use Bengr\Admin\Navigation\UserMenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class AdminManager
{
    protected bool $isNavigationMounted = false;

    protected array $navigationGroups = [];

    protected array $navigationItems = [];

    protected array $userMenuItems = [];

    protected array $pages = [];

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
            });
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

    public function getPageByUrl($url)
    {
        $page = collect($this->getPages())->first(function ($page) use ($url) {
            return app($page)->getRouteUrl() === $url;
        });

        if (!$page) return null;

        return app($page);
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
