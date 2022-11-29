<?php

namespace Bengr\Admin\Tables\Columns\Concerns;


trait CanBeSearchable
{
    protected bool $isSearchable = false;

    public function searchable(bool $searchable = true): static
    {
        $this->isSearchable = $searchable;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }
}
