<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait HasState
{
    protected array $state = [];

    public function getCachedValue(?string $name = null)
    {
        if ($name) {
            return array_key_exists($name, $this->state) ? $this->state[$name] : null;
        }

        return $this->state;
    }

    protected function getFormState(): array
    {
        return $this->state;
    }

    public function fill(array | Model $payload = null): self
    {
        if ($payload instanceof Model) {
            collect($this->getFormInputs())->each(function (Input $input) use ($payload) {
                $input->value(Arr::get($payload, $input->getName()));
            });
        } else if (!$payload) {
            collect($this->getFormInputs())->each(function (Input $input) {
                $this->state[$input->getName()] = $input->getValue();
            });
        } else {
            $this->state = $payload;
        }

        return $this;
    }
}
