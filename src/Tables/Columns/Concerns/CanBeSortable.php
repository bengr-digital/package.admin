<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

trait CanBeSortable
{
    protected bool $isSortable = false;

    public function sortable(bool $sortable = true): static
    {
        $this->isSortable = $sortable;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }
}
