<?php

namespace Bengr\Admin\Navigation;

class NavigationItem
{
    protected string $label;

    protected string $icon;

    protected ?string $activeIcon = null;

    protected ?string $group = null;

    protected ?int $sort = null;

    protected ?string $badge = null;

    protected ?string $badgeColor = null;

    protected string $routeName;

    protected string $routeUrl;

    final public function __construct(?string $label = null)
    {
        if (filled($label)) {
            $this->label($label);
        }
    }

    public static function make(?string $label = null): static
    {
        return app(static::class, ['label' => $label]);
    }

    public function page(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function activeIcon(string $activeIcon): self
    {
        $this->activeIcon = $activeIcon;

        return $this;
    }

    public function group(?string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function sort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function badge(?string $badge, ?string $color = null): self
    {
        $this->badge = $badge;
        $this->badgeColor = $color;

        return $this;
    }

    public function route(string $name, string $url): self
    {
        $this->routeName = $name;
        $this->routeUrl = $url;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getActiveIcon(): ?string
    {
        return $this->activeIcon;
    }

    public function getGroup(): ?string
    {
        return $this->group ?? '';
    }

    public function getSort(): ?int
    {
        return $this->sort ?? -1;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function getBadgeColor(): ?string
    {
        return $this->badgeColor;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function getRouteUrl(): string
    {
        return $this->routeUrl;
    }
}
