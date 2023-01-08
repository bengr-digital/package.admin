<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeMultiple
{
    protected bool $isMultiple = false;

    public function multiple(bool $isMultiple = true): self
    {
        $this->isMultiple = $isMultiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }
}
