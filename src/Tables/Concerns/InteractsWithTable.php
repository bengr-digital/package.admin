<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Tables\Table;
use Bengr\Admin\Tables\Concerns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

    protected Table $table;

    public function getTable(Request $request): Table
    {
        if (!isset($this->table)) {
            $this->table = Table::make($this, $request);
        }

        return $this->table;
    }

    protected function getTableQuery(): Builder
    {
        return app($this->getTableModel())->query();
    }
}
