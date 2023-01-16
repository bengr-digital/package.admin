<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait HasWidth
{
    protected ?int $width = null;

    public function width(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }
}
