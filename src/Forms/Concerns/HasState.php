<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Widgets\Inputs\FileInput;
use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

    public function fill(array | Model | null $data = null): self
    {
        collect($this->getFormInputs())->each(function (Input $input) use ($data) {
            $input->value($input->getValueFromData($data));
            $parts = Str::of($input->getName())->explode('.');

            if ($parts->first() !== $parts->last()) {
                if (!array_key_exists($parts->first(), $this->state)) {
                    $this->state[$parts->first()] = [];
                }

                $this->state[$parts->first()][$parts->last()] = $input->getValue();
            } else {
                $this->state[$parts->first()] = $input->getValue();
            }
        });

        return $this;
    }
}
