<?php

namespace Bengr\Admin\Widgets;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Widget
{
    protected ?int $sort = null;

    protected ?int $id = null;

    protected ?string $name = null;

    protected int $columnSpan = 12;

    public function columnSpan(int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function column(int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function id(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort ?? -1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? Str::of(class_basename(static::class))
            ->kebab()
            ->slug();
    }

    public function getColumnSpan(): int
    {
        return $this->columnSpan;
    }

    public function hasWidgets(): bool
    {
        return false;
    }

    public function getWidgets(): array
    {
        return [];
    }

    public function getData(Request $request): ?array
    {
        return null;
    }
}
