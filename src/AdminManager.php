<?php

namespace Bengr\Admin;

use Bengr\Admin\Navigation;
use Bengr\Admin\Pages\Page;
use Illuminate\Support\Collection;

class AdminManager
{
    protected bool $isNavigationMounted = false;

    protected array $navigationGroups = [];

    protected array $navigationItems = [];

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

    public function getPage($name)
    {
        $page = collect($this->getPages())->first(function ($page) use ($name) {
            return app($page)->getRouteName() === $name;
        });

        if (!$page) return null;

        return app($page);
    }
}
