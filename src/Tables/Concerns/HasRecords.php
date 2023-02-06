<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

trait HasRecords
{
    protected Collection | Paginator | null $records = null;

    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(SupportCollection $params): Collection | Paginator
    {
        if ($this->records) return $this->records;

        $query = $this->getTableQuery();

        $this->applyFiltersToTableQuery($query, $params);
        $this->applySortingToTableQuery($query, $params);
        $this->applySearchToTableQuery($query, $params);

        foreach ($this->getCachedTableColumns() as $column) {
            $column->applyEagerLoading($query);
            $column->applyRelationshipAggregates($query);
        }

        if ($this->isTablePaginationEnabled()) {
            $this->records = $this->paginateTableQuery($query, $params);
        } else {
            $this->records = $query->get();
        }

        return $this->records;
    }
}
