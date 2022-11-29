<?php

namespace Bengr\Admin\Tables\Contracts;

use Illuminate\Database\Eloquent\Model;

interface HasTable
{
    public function getCachedTableColumns(): array;

    public function getCachedTableActions(): array;

    public function getCachedTableBulkActions(): array;

    public function getCachedTableModel(): ?Model;
}
