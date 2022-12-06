<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

trait HasPagination
{

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }

    protected function paginateTableQuery(Builder $query, ?int $page = 1): Paginator
    {
        $records = $query->paginate($this->getTablePaginationPerPage(), ['*'], $this->getTablePaginationPageName(), $page);

        return $records;
    }

    protected function getTablePaginationPerPage(): int
    {
        return config('admin.tables.pagination.per_page') ?? 15;
    }

    protected function getTablePaginationPageName(): string
    {
        return config('admin.tables.pagination.page_name') ?? 'page';
    }
}
