<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait InteractsWithTableQuery
{
    public function applyEagerLoading(Builder $query): Builder
    {
        if ($this->hasRelationship($query->getModel())) {
            $query->with([$this->getRelationshipName()]);
        }

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

    public function getRelationshipName(): string
    {
        return (string) Str::of($this->getName())->beforeLast('.');
    }
}
