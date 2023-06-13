<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeReadonly
{
    protected bool $isReadonly = false;

    public function readonly(bool $isReadonly = true): self
    {
        $this->isReadonly = $isReadonly;

        return $this;
    }

    public function isReadonly(): bool
    {
        return $this->isReadonly;
    }
}
