<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Tables\Columns\Column;

trait HasColumns
{
    protected array $cachedTableColumns = [];

    public function getCachedTableColumns(): array
    {
        foreach ($this->getTableColumns() as $column) {
            $this->cachedTableColumns[$column->getName()] = $column;
        }

        return $this->cachedTableColumns;
    }

    public function getTableColumn(string $name): ?Column
    {
        return $this->getCachedTableColumns()[$name] ?? null;
    }

    protected function getTableColumns(): array
    {
        return [];
    }
}
