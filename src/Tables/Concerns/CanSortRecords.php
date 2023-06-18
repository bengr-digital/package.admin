<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait CanSortRecords
{
    protected function applySortingToTableQuery(Builder $query, Collection $params): Builder
    {
        $sortColumn = $this->getTableSortColumn($params);

        if (!$sortColumn) {
            $query->orderBy('created_at', 'desc');

            return $query;
        }


        $sortDirection = Str::of($this->getTableSortDirection($params))->lower()->value() === 'asc' ? 'asc' : 'desc';

        $column = $this->getTableColumn($sortColumn);

        if ($column && (!$column->isHidden()) && $column->isSortable()) {
            $column->applySort($query, $sortDirection);

            return $query;
        }

        return $query;
    }

    protected function getTableSortColumn(Collection $params): ?string
    {
        /** @var string */
        $param = $params->get($this->getTableSortingSortColumnParam());

        return $param;
    }

    protected function getTableSortDirection(Collection $params): ?string
    {
        /** @var string */
        $param = $params->get($this->getTableSortingSortOrderParam());

        return $param;
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
