<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait CanSearchRecords
{
    protected function applySearchToTableQuery(Builder $query, Request $request): Builder
    {
        $searchQuery = $this->getTableSearchQuery($request);

        if (!$searchQuery) return $query;

        dd($searchQuery);

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
