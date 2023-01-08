<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasValue
{
    protected ?string $value = null;

    public function value(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
