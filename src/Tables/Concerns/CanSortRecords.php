<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait CanSortRecords
{
    protected function applySortingToTableQuery(Builder $query, Request $request): Builder
    {
        $sortColumn = $this->getTableSortColumn($request);

        if (!$sortColumn) return $query;

        $sortDirection = Str::of($this->getTableSortDirection($request))->lower()->value() === 'desc' ? 'desc' : 'asc';

        $column = $this->getTableColumn($sortColumn);

        if ($column && (!$column->isHidden()) && $column->isSortable()) {
            $column->applySort($query, $sortDirection);

            return $query;
        }

        return $query;
    }

    protected function getTableSortColumn(Request $request): ?string
    {
        return $request->get($this->getTableSortingSortColumnParam());
    }

    protected function getTableSortDirection(Request $request): ?string
    {
        return $request->get($this->getTableSortingSortOrderParam());
    }

    protected function getTableSortingSortColumnParam(): string
    {
        return config('admin.tables.sorting.params.sort_column') ?? 'sort_column';
    }

    protected function getTableSortingSortOrderParam(): string
    {
        return config('admin.tables.sorting.params.sort_order') ?? 'sort_order';
    }
}
