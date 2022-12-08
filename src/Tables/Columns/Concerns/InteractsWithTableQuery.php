<?php

namespace Bengr\Admin\Tables\Columns\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait InteractsWithTableQuery
{
    public function applyEagerLoading(Builder $query): Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        if ($this->hasRelationship($query->getModel())) {
            $query->with([$this->getRelationshipName()]);
        }

        return $query;
    }

    public function applySort(Builder $query, string $direction = 'asc'): Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        if (!$this->isSortable()) {
            return $query;
        }

        $relationship = $this->getRelationship($query->getModel());

        $query->when(
            $relationship,
            fn ($query) => $query->orderBy(
                $relationship
                    ->getRelationExistenceQuery(
                        $relationship->getRelated()::query(),
                        $query,
                        $this->getRelationshipColumnName(),
                    )
                    ->applyScopes()
                    ->getQuery(),
                $direction,
            ),
            fn ($query) => $query->orderBy($this->getName(), $direction),
        );

        return $query;
    }

    public function applySearch(Builder $query, string $searchQuery = '', bool $isFirst): Builder
    {
        if (!$searchQuery) {
            return $query;
        }

        if ($this->isHidden()) {
            return $query;
        }

        if (!$this->isSearchable()) {
            return $query;
        }

        $relationship = $this->getRelationship($query->getModel());
        $whereClause = $isFirst ? 'where' : 'orWhere';

        $query->when(
            $relationship,
            fn ($query) => $query->{"{$whereClause}Relation"}($this->getRelationshipName(), $this->getRelationshipColumnName(), 'like', "%{$searchQuery}%"),
            fn ($query) => $query->{$whereClause}($this->getName(), 'like', "%{$searchQuery}%"),
        );

        return $query;
    }

    public function hasRelationship(Model $model): bool
    {
        return $this->getRelationship($model) !== null;
    }

    public function getRelationship(Model $model): ?Relation
    {
        if (!Str::of($this->getName())->contains('.')) {
            return null;
        }

        $relationship = null;

        foreach (explode('.', $this->getRelationshipName()) as $nestedRelationshipName) {
            if (!$model->isRelation($nestedRelationshipName)) {
                $relationship = null;

                break;
            }

            $relationship = $model->{$nestedRelationshipName}();
            $model = $relationship->getRelated();
        }

        return $relationship;
    }

    public function getRelationshipColumnName(): string
    {
        return (string) Str::of($this->getName())->afterLast('.');
    }

    public function getRelationshipName(): string
    {
        return (string) Str::of($this->getName())->beforeLast('.');
    }
}
