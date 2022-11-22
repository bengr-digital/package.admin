<?php

namespace Bengr\Admin\Pages;

use Illuminate\Support\Str;

class Page
{
    protected ?string $navigationIcon = null;

    protected ?string $activeNavigationIcon = null;

    protected ?string $navigationGroup = null;

    protected ?string $navigationLabel = null;

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $slug = null;

    protected ?string $heading = null;

    protected ?string $subheading = null;

    protected string | array $middlewares = [];

    public function getMiddlewares(): string | array
    {
        return $this->middlewares;
    }

    public function getSlug(): string
    {
        return $this->slug ?? Str::of($this->title ?? class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getRouteName(): string
    {
        $slug = $this->getSlug();

        return "admin.pages.{$slug}";
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

    protected function getNavigationIcon(): ?string
    {
        return $this->navigationIcon;
    }

    protected function getActiveNavigationIcon(): ?string
    {
        return $this->activeNavigationIcon;
    }

    protected function getNavigationBadge(): ?string
    {
        return null;
    }

    protected function getNavigationBadgeColor(): ?string
    {
        return null;
    }
}
