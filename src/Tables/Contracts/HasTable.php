<?php

namespace Bengr\Admin\Tables\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface HasTable
{
    public function getCachedTableColumns(): array;

    public function getCachedTableActions(): array;

    public function getCachedTableBulkActions(): array;

    public function getTableRecords(): Collection | Paginator;
}
