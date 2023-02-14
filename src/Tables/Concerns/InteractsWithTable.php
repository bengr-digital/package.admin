<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Tables\Table;
use Bengr\Admin\Tables\Concerns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait InteractsWithTable
{
    use Concerns\CanSortRecords;
    use Concerns\CanSearchRecords;
    use Concerns\HasPagination;
    use Concerns\HasRecords;
    use Concerns\HasColumns;
    use Concerns\HasActions;
    use Concerns\HasFilters;
    use Concerns\HasBulkActions;
    use Concerns\HasActionOnClick;

    protected Table $table;

    public function getTable(Collection $params): Table
    {
        if (!isset($this->table)) {
            $this->table = Table::make($this, $params);
        }

        return $this->table;
    }

    protected function getTableQuery(): Builder
    {
        return app($this->getTableModel())->query();
    }
}
