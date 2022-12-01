<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

trait HasPagination
{

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        $records = $query->paginate();

        return $records;
    }
}
