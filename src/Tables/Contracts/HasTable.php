<?php

namespace Bengr\Admin\Tables\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface HasTable
{
    public function getCachedTableColumns(): array;

    public function getCachedTableActions(): array;

    public function getCachedTableBulkActions(): array;

    public function getTableRecords(Request $request): Collection | Paginator;
}
