<?php

namespace Bengr\Admin\Modals;

class Modal
{
    protected ?int $id = null;

    protected ?string $type = null;

    protected ?string $heading = null;

    protected ?string $subheading = null;

    protected ?string $direction = null;

    protected array $widgets = [];

    protected array $actions = [];

    protected bool $hasCross = false;

    final public function __construct()
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }


    public function id(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function heading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function subheading(string $subheading): self
    {
        $this->subheading = $subheading;

        return $this;
    }

    public function direction(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function widgets(array $widgets): self
    {
        $this->widgets = $widgets;

        return $this;
    }

    public function cross(?bool $condition): self
    {
        $this->hasCross = $condition ?? true;

        return $this;
    }

    public function actions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type ?? 'card';
    }

    public function getDirection(): ?string
    {
        return $this->direction ?? $this->getType() === 'drawer' ? 'right' : 'center';
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function getSubheading(): ?string
    {
        return $this->subheading;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function hasCross(): bool
    {
        return $this->hasCross;
    }
}
