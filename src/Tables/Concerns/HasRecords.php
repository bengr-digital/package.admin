<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasRecords
{
    protected function getTableModel(): ?string
    {
        return null;
    }

    public function getTableRecords(): Collection | Paginator
    {
        $query = $this->getTableQuery();

        return $this->getTableQuery()->select('username')->paginate();
    }
}
