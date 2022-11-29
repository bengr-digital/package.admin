<?php

namespace Bengr\Admin\Tables\Concerns;


trait HasColumns
{
    public function getCachedTableColumns(): array
    {
        return $this->getTableColumns();
    }

    protected function getTableColumns(): array
    {
        return [];
    }
}
