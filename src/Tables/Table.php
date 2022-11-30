<?php

namespace Bengr\Admin\Tables;

use Bengr\Admin\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Table
{
    protected HasTable $tableResource;

    final public function __construct($tableResource)
    {
        $this->tableResource = $tableResource;
    }

    public static function make(HasTable $tableResource): static
    {
        return app(static::class, ['tableResource' => $tableResource]);
    }

    public function getColumns(): array
    {
        return $this->tableResource->getCachedTableColumns();
    }

    public function getActions(): array
    {
        return $this->tableResource->getCachedTableActions();
    }

    public function getBulkActions(): array
    {
        return $this->tableResource->getCachedTableBulkActions();
    }

    public function getModel(): ?Model
    {
        return $this->tableResource->getCachedTableModel();
    }

    public function getRecords(): Collection | Paginator
    {
        return $this->tableResource->getTableRecords();
    }
}
