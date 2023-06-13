<?php

namespace Bengr\Admin\Tables\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait InteractsWithTableQuery
{
    protected ?\Closure $modifyQueryUsing = null;

    public function apply(Builder $query, Collection $data): Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        if (!$this->hasQueryModificationCallback()) {
            return $query;
        }

        $callback = $this->modifyQueryUsing;

        $this->evaluate($callback, [
            'query' => $query,
            'data' => $data
        ]);

        return $query;
    }

    public function query(?\Closure $callback): static
    {
        $this->modifyQueryUsing = $callback;

        return $this;
    }

    protected function hasQueryModificationCallback(): bool
    {
        return $this->modifyQueryUsing instanceof \Closure;
    }
}
