<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait HasRecords
{
    protected Collection | Paginator | null $records = null;

    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(Request $request): Collection | Paginator
    {
        if ($this->records) return $this->records;

        $query = $this->getTableQuery();

        $this->applySortingToTableQuery($query, $request);
        $this->applySearchToTableQuery($query, $request);

        foreach ($this->getCachedTableColumns() as $column) {
            $column->applyEagerLoading($query);
        }

        if ($this->isTablePaginationEnabled()) {
            $this->records = $this->paginateTableQuery($query, $request);
        } else {
            $this->records = $query->get();
        }

        return $this->records;
    }
}
