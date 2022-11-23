<?php

namespace Bengr\Admin;

class AdminManager
{
    protected bool $isNavigationMounted = false;

    protected array $navigationItems = [];

    protected array $pages = [];

    public function mountNavigation(): void
    {
        foreach ($this->getPages() as $page) {
            app($page)->registerNavigationItems();
        }

        foreach ($this->getNavigationItems() as $item) {
            // if $item->parent -> get navigationitem where 
            $item->registerChildren();
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

    public function getNavigation(): array
    {
        if (!$this->isNavigationMounted) {
            $this->mountNavigation();
        }

        return collect($this->getNavigationItems())->all();
    }

    public function getNavigationItems(): array
    {
        return $this->navigationItems;
    }

    public function getPages(): array
    {
        return array_unique($this->pages);
    }
}
