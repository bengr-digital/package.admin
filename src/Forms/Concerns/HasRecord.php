<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasRecord
{
    protected ?Model $record = null;

    protected ?string $model = null;

    public function record(Model | null $entity): self
    {
        if ($this->getRecord() || !$entity) return $this;

        $this->record = $entity;

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
