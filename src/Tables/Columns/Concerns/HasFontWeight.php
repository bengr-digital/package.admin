<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait HasFontWeight
{
    protected string | \Closure | null $weight = null;

    public function weight(string | \Closure | null $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->evaluate($this->weight);
    }
}
