<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait HasColor
{
    protected string | \Closure | null $color = null;

    public function color(string | \Closure | null $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->evaluate($this->color);
    }
}
