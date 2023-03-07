<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeRequired
{
    protected bool $isRequired = false;

    public function required(bool $isRequired = true): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired || in_array('required', $this->rules);
    }
}
