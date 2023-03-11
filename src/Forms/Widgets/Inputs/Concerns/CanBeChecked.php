<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeChecked
{
    protected bool $isChecked = false;

    public function checked(bool $isChecked = true): self
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    public function isChecked(): bool
    {
        return $this->isChecked;
    }

    public function checkable(): bool
    {
        return true;
    }
}
