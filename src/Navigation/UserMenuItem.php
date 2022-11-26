<?php

namespace Bengr\Admin\Navigation;

class UserMenuItem
{
    protected string $label;

    protected string $icon;

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

    public function icon(string $icon): self
    {
        $this->icon = $icon;

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

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getSort(): ?int
    {
        return $this->sort ?? -1;
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
