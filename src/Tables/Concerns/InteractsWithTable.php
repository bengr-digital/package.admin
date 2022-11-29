<?php

namespace Bengr\Admin\Tables\Concerns;

use Bengr\Admin\Tables\Table;
use Bengr\Admin\Tables\Concerns;

trait InteractsWithTable
{
    use Concerns\HasModel;
    use Concerns\HasColumns;
    use Concerns\HasActions;
    use Concerns\HasBulkActions;

    protected Table $table;

    public function getTable(): Table
    {
        if (!isset($this->table)) {
            $this->table = Table::make($this);
        }

        return $this->table;
    }
}
