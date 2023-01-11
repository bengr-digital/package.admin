<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasValue
{
    protected null | string | array | bool | int $value = null;

    public function value(null | string | array | bool | int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): null | string | array | bool | int
    {
        return $this->value;
    }
}
