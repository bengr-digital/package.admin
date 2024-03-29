<?php

namespace Bengr\Admin\Navigation;

class UserMenuItem
{
    protected string $label;

    protected string $iconName;

    protected string $iconType;

    protected ?string $activeIconName = null;

    protected ?string $activeIconType = null;

    protected ?int $sort = null;

    protected string $routeName;

    protected string $routeUrl;

    final public function __construct()
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function icon(string $iconName, ?string $iconType = 'outlined'): self
    {
        $this->iconName = $iconName;
        $this->iconType = $iconType;

        return $this;
    }

    public function activeIcon(?string $activeIconName, ?string $activeIconType = 'outlined'): self
    {
        $this->activeIconName = $activeIconName;
        $this->activeIconType = $activeIconType;

        return $this;
    }

    public function sort(?int $sort): self
    {
        $this->sort = $sort;

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

    public function getIconName(): string
    {
        return $this->iconName;
    }

    public function getIconType(): string
    {
        return $this->iconType;
    }

    public function getActiveIconName(): ?string
    {
        return $this->activeIconName ?? $this->getIconName();
    }

    public function getActiveIconType(): ?string
    {
        return $this->activeIconType ?? $this->getIconType();
    }

    public function getSort(): ?int
    {
        return $this->sort ?? 0;
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
