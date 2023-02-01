<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasRecord
{
    protected ?Model $record = null;

    protected ?string $model = null;

    protected function record(?Page $page): self
    {
        $param = collect($page->getParams())->first(function ($param) {
            return $param['table'] === app($this->getModel())->getTable();
        });

        if (!$param) {
            $this->record = null;
        } else {
            $query = app($this->getModel())->query();
            $query->where($param['column'], $param['value']);

            foreach ($this->getFormInputs() as $input) {
                $input->applyEagerLoading($query);
            }

            $this->record = $query->first();
        }

        return $this;
    }

    protected function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    protected function getRecord(): ?Model
    {
        return $this->record;
    }

    protected function getModel(): ?string
    {
        return $this->model;
    }
}
