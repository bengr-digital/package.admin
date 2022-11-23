<?php

namespace Bengr\Admin\Navigation;


class NavigationItem
{
    protected ?string $group = null;

    protected ?string $parent = null;

    protected array $children = [];

    protected string $icon;

    protected ?string $activeIcon = null;

    protected string $label;

    protected ?string $badge = null;

    protected ?string $badgeColor = null;

    protected ?int $sort = null;

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

    public function badge(?string $badge, ?string $color = null): self
    {
        $this->badge = $badge;
        $this->badgeColor = $color;

        return $this;
    }

    public function group(?string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function parent(?string $parent): self
    {
        $this->parent = $parent;

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

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function sort(?int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function registerChildren(): void
    {
        $this->children = ['fsdfs'];
    }
}
