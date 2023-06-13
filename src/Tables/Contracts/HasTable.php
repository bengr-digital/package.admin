<?php

namespace Bengr\Admin\Tables\Contracts;

use Bengr\Admin\Actions\Action;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;

interface HasTable
{
    public function getCachedTableColumns(): array;

    public function getCachedTableActions(): array;

    public function getCachedTableActionOnClick(): ?Action;

    public function getCachedTableFilters(): array;

    public function getCachedTableBulkActions(): array;

    public function getTableRecords(SupportCollection $params): Collection | Paginator;
}
