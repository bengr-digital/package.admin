<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasRecords
{
    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(): Collection | Paginator
    {
        $query = $this->getTableQuery();

        foreach ($this->getCachedTableColumns() as $column) {
            $column->applyEagerLoading($query);
        }

        if ($this->isTablePaginationEnabled()) {
            return $this->paginateTableQuery($query);
        }

        return $query->get();
    }
}
