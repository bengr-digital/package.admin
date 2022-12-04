<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasRecords
{
    protected Collection | Paginator | null $records = null;

    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(): Collection | Paginator
    {
        if ($this->records) return $this->records;

        $query = $this->getTableQuery();

        foreach ($this->getCachedTableColumns() as $column) {
            $column->applyEagerLoading($query);
        }

        if ($this->isTablePaginationEnabled()) {
            $this->records = $this->paginateTableQuery($query);
        } else {
            $this->records = $query->get();
        }

        return $this->records;
    }
}
