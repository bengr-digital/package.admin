<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait CanBeSortable
{
    protected bool $isSortable = false;

    public function sortable(bool $isSortable = true): self
    {
        $this->isSortable = $isSortable;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }
}
