<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait HasState
{
    protected array $state = [];

    protected function getFormState(): array
    {
        return $this->state;
    }

    public function fill(?Model $record = null): self
    {
        if ($record) {
            collect($this->getFormInputs())->each(function (Input $input) use ($record) {
                $input->value(Arr::get($record, $input->getName()));
            });
        }

        collect($this->getFormInputs())->each(function (Input $input) {
            $this->state[$input->getName()] = $input->getValue();
        });

        return $this;
    }
}
