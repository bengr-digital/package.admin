<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

trait HasFilters
{
    protected function applyFiltersToTableQuery(Builder $query, Collection $params): Builder
    {
        $data = $params->get($this->getTableFilterParam());
        if (!$data) return $query;

        foreach ($this->getCachedTableFilters() as $filter) {
            $filter->apply(
                $query,
                !$filter->getName() ? collect($data) : collect($data[$filter->getName()] ?? []),
            );
        }

        return $query;
    }

    protected function getTableFilterParam(): string
    {
        return config('admin.widgets.tables.filter.params.filter') ?? 'filter';
    }

    public function getCachedTableFilters(): array
    {
        return $this->getTableFilters();
    }

    protected function getTableFilters(): array
    {
        return [];
    }
}
