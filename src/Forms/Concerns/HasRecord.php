<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasRecord
{
    protected ?Model $record = null;

    protected ?string $model = null;

    public function record(Model | Page | null $entity): self
    {
        if ($this->getRecord() || !$entity) return $this;

        if ($entity instanceof Model) {
            $this->record = $entity;
        }

        if ($entity instanceof Page) {
            $param = collect($entity->getParams())->first(function ($param) {
                return $param['table'] === app($this->getModel())->getTable();
            });

            if (!$param) {
                $this->record = null;
            } else {
                $query = in_array(SoftDeletes::class, class_uses($this->getModel()), true) ? app($this->getModel())->query()->withTrashed() : app($this->getModel())->query();
                $query->where($param['column'], $param['value']);

                foreach ($this->getFormInputs() as $input) {
                    $input->applyEagerLoading($query);
                }

                $this->record = $query->first();
            }
        }

        return $this;
    }

    protected function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }
}
