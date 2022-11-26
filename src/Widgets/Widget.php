<?php

namespace Bengr\Admin\Widgets;

use Illuminate\Support\Str;

class Widget
{
    protected ?int $sort = null;

    protected ?string $name = null;

    protected int $columnSpan = 12;

    public function columnSpan(int $columnSpan): self
    {
        $this->columnSpan = $columnSpan;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort ?? -1;
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

    public function getData(): array
    {
        return [];
    }
}
