<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait CanSortRecords
{
    public $tableSortColumn = null;

    public $tableSortDirection = null;

    protected function applySortingToTableQuery(Builder $query): Builder
    {
        return $query;
    }
}
