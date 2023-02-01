<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasValue
{
    protected $value = null;

    public function transformValue()
    {
        return $this->value;
    }

    public function value($value = null): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->getType() === 'password' ? null : $this->value;
    }
}
