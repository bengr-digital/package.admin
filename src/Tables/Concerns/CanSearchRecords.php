<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait CanSearchRecords
{
    protected function applySearchToTableQuery(Builder $query, Collection $params): Builder
    {
        $searchQuery = $this->getTableSearchQuery($params);
        $index = 0;

        if (!$searchQuery) return $query;

        foreach ($this->getTableSearchableColumns() as $column) {

            $column->applySearch($query, $searchQuery, $index === 0 ? true : false);

            $index++;
        };

        return $query;
    }

    protected function getTableSearchQuery(Collection $params): ?string
    {
        /** @var string */
        $param = $params->get($this->getTableSearchQueryParam());

        return $param;
    }

    protected function getTableSearchQueryParam(): string
    {
        return config('admin.tables.search.params.query') ?? 'q';
    }
}
