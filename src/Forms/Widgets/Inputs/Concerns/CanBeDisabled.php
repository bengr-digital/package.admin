<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeDisabled
{
    protected bool $isDisabled = false;

    public function disabled(bool $isDisabled = true): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }

    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }
}
