<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasQuery
{
    private function getTableQueryQuery(): ?\Closure
    {
        return null;
    }

    protected function applyQueryToTableQuery(Builder $query, Collection $params)
    {
        if ($this->getTableQueryQuery()) {
            $this->evaluate($this->getTableQueryQuery(), [
                'query' => $query,
                'params' => $params
            ]);
        }
    }
}
