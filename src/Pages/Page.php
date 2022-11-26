<?php

namespace Bengr\Admin\Pages;

use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Navigation\NavigationItem;
use Illuminate\Support\Str;

class Page
{
    protected ?string $layout = 'app';

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $slug = null;

    protected string | array $middlewares = [];

    protected ?string $navigationLabel = null;

    protected ?string $navigationIcon = null;

    protected ?string $navigationActiveIcon = null;

    protected ?string $navigationGroup = null;

    protected ?int $navigationSort = null;

    protected bool $inNavigation = true;

    public function registerNavigationItems(): void
    {
        if (!$this->inNavigation()) {
            return;
        }

        Admin::registerNavigationItems($this->getNavigationItems());
    }

    public function getNavigationItems(): array
    {
        return [
            NavigationItem::make($this->getNavigationLabel())
                ->group($this->getNavigationGroup())
                ->icon($this->getNavigationIcon())
                ->activeIcon($this->getNavigationActiveIcon())
                ->sort($this->getNavigationSort())
                ->badge($this->getNavigationBadge(), $this->getNavigationBadgeColor())
                ->route($this->getRouteName(), $this->getRouteUrl())
        ];
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

    protected function inNavigation(): bool
    {
        return $this->inNavigation;
    }
}
