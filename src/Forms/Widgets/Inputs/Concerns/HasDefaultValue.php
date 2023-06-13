<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasDefaultValue
{
    protected null | string | array | bool | int $defaultValue = null;

    public function default(null | string | array | bool | int $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        if (!$this->getValue()) {
            $this->value = $defaultValue;
        }

        return $this;
    }

    public function getDefaultValue(): null | string | array | bool | int
    {
        return $this->defaultValue;
    }
}
