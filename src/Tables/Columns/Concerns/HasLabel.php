<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

use Illuminate\Support\Str;

trait HasLabel
{
    protected string $label;

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label ?? Str::of($this->getName())
            ->beforeLast('.')
            ->afterLast('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();;
    }
}
