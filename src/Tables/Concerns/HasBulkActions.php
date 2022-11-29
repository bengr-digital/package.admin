<?php

namespace Bengr\Admin\Tables\Concerns;


trait HasBulkActions
{
    public function getCachedTableBulkActions(): array
    {
        return $this->getTableBulkActions();
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }
}
