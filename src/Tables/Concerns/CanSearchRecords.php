<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait CanSearchRecords
{
    protected function applySearchToTableQuery(Builder $query, Request $request): Builder
    {
        $searchQuery = $this->getTableSearchQuery($request);
        $index = 0;

        if (!$searchQuery) return $query;

        foreach ($this->getTableSearchableColumns() as $column) {

            $column->applySearch($query, $searchQuery, $index === 0 ? true : false);

            $index++;
        };

        return $query;
    }

    protected function getTableSearchQuery(Request $request): ?string
    {
        return $request->get($this->getTableSearchQueryParam());
    }

    protected function getTableSearchQueryParam(): string
    {
        return config('admin.tables.search.params.query') ?? 'q';
    }
}
