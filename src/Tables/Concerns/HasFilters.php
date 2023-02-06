<?php

namespace Bengr\Admin\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait HasFilters
{
    protected function applyFiltersToTableQuery(Builder $query, Collection $params): Builder
    {
        $data = $params->get($this->getTableFilterParam());
        if (!$data) return $query;

        return $query->where(function (Builder $query) use ($data) {
            foreach ($this->getCachedTableFilters() as $filter) {
                $filter->apply(
                    $query,
                    !$filter->getName() ? $data : $data[$filter->getName()] ?? [],
                );
            }
        });
    }

    protected function getTableFilterParam(): string
    {
        return config('admin.tables.filter.params.filter') ?? 'filter';
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
