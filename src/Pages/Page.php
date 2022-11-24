<?php

namespace Bengr\Admin\Pages;

use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Navigation\NavigationItem;
use Illuminate\Support\Str;

class Page
{
    protected ?string $layout = 'app';

    protected ?string $navigationIcon = null;

    protected ?string $activeNavigationIcon = null;

    protected ?string $navigationGroup = null;

    protected ?string $navigationParent = null;

    protected ?string $navigationLabel = null;

    protected ?int $navigationSort = null;

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $heading = null;

    protected ?string $subheading = null;

    protected string | array $middlewares = [];

    protected array $navigationItem = [];

    public function registerNavigationItems(): void
    {
        Admin::registerNavigationItems($this->getNavigationItems());
    }

    public function getNavigationItems(): array
    {
        if (!$this->navigationItem) {
            $this->navigationItem = [
                NavigationItem::make($this->getNavigationLabel())
                    ->group($this->getNavigationGroup())
                    ->route($this->getRoute())
                    ->parent($this->getNavigationParent())
                    ->icon($this->getNavigationIcon())
                    ->activeIcon($this->getActiveNavigationIcon())
                    ->badge($this->getNavigationBadge(), $this->getNavigationBadgeColor())
                    ->sort($this->getNavigationSort())
            ];
        }

        return $this->navigationItem;
    }

    public function getMiddlewares(): string | array
    {
        return $this->middlewares;
    }

    public function getSlug(): string
    {
        return Str::of($this->title ?? class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getRoute(): PageRoute
    {
        return PageRoute::make($this->getRouteName(), $this->getRouteUri());
    }

    protected function getRouteName(): string
    {
        $slug = $this->getSlug();

        return "admin.pages.{$slug}";
    }

    protected function getRouteUri(): string
    {
        $slug = $this->getSlug();

        return "/{$slug}";
    }

    public function getTitle(): string
    {
        return $this->title ?? (string) Str::of(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getNavigationLabel(): ?string
    {
        return $this->navigationLabel;
    }

    protected function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    protected function getNavigationParent(): ?string
    {
        return $this->navigationParent;
    }

    protected function getNavigationIcon(): ?string
    {
        return $this->navigationIcon ?? 'document';
    }

    protected function getActiveNavigationIcon(): ?string
    {
        return $this->activeNavigationIcon ?? $this->getNavigationIcon();
    }

    protected function getNavigationBadge(): ?string
    {
        return null;
    }

    protected function getNavigationBadgeColor(): ?string
    {
        return null;
    }

    protected function getNavigationSort(): ?string
    {
        return $this->navigationSort;
    }
}
