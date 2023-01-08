<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeSearchable
{
    protected bool $isSearchable = false;

    public function searchable(bool $isSearchable = true): self
    {
        $this->isSearchable = $isSearchable;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }
}
