<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait HasFontSize
{
    protected string | \Closure | null $size = null;

    public function size(string | \Closure | null $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->evaluate($this->size);
    }
}
