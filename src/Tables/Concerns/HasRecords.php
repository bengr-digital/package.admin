<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

trait HasRecords
{
    protected Collection | Paginator | null $records = null;

    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(?int $page = 1): Collection | Paginator
    {
        if ($this->records) return $this->records;

        $query = $this->getTableQuery();

        foreach ($this->getCachedTableColumns() as $column) {
            $column->applyEagerLoading($query);
        }

        if ($this->isTablePaginationEnabled()) {
            $this->records = $this->paginateTableQuery($query, $page);
        } else {
            $this->records = $query->get();
        }

        return $this->records;
    }
}
