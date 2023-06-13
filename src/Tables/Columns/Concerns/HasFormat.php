<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait HasFormat
{
    protected ?string $format = null;

    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function hasFormat(): bool
    {
        return $this->format ? true : false;
    }

    public function getFormat(): ?string
    {
        return $this->format ?? null;
    }
}
