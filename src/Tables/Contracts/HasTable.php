<?php

namespace Bengr\Admin\Tables\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

interface HasTable
{
    public function getCachedTableColumns(): array;

    public function getCachedTableActions(): array;

    public function getCachedTableBulkActions(): array;

    public function getTableRecords(SupportCollection $params): Collection | Paginator;
}
