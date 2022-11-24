<?php

namespace Bengr\Admin\Navigation;

use Illuminate\Contracts\Support\Arrayable;

class NavigationGroup
{
    protected ?string $label = null;

    protected array | Arrayable $items = [];

    final public function __construct(?string $label = null)
    {
        $this->label($label);
    }

    public static function make(?string $label = null): static
    {
        return app(static::class, ['label' => $label]);
    }

    public function items(array | Arrayable $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function label(?string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getItems(): array | Arrayable
    {
        return $this->items;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }
}
