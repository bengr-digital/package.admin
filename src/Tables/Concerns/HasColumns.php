<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Tables\Columns\Column;

trait HasColumns
{
    protected array $cachedTableColumns = [];

    public function getCachedTableColumns(): array
    {
        $this->cachedTableColumns = [];

        foreach ($this->getTableColumns() as $column) {
            $this->cachedTableColumns[] = $column;
        }

        return $this->cachedTableColumns;
    }

    public function getTableColumn(string $name): ?Column
    {
        return collect($this->getCachedTableColumns())->first(function ($column) use ($name) {
            return $column->getName() === $name;
        }) ?? null;
    }

    public function getTableSearchableColumns(): array
    {
        return collect($this->getCachedTableColumns())->filter(function ($column) {
            return $column->isSearchable();
        })->toArray();
    }

    protected function getTableColumns(): array
    {
        return [];
    }
}
