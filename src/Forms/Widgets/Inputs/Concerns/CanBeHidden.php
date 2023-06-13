<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeHidden
{
    protected bool $isHidden = false;

    public function hidden(bool $isHidden = true): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function isHidden(): bool
    {
        return $this->isHidden;
    }
}
