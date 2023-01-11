<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\Input;

trait HasState
{
    protected array $state = [];

    protected function getFormState(): array
    {
        return $this->state;
    }

    protected function fillState(array $state)
    {
        $this->state = $state;
    }

    protected function fillDefaultState()
    {
        collect($this->getFormInputs())->each(function (Input $input) {
            $this->state[$input->getName()] = $input->getValue();
        });
    }
}
