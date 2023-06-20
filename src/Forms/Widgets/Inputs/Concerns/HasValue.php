<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait HasValue
{
    protected $value = null;

    public function transformValue()
    {
        return $this->value;
    }

    public function value($value = null): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getValueFromData(array | Model | null $data)
    {
        if (!$data || !Arr::has($data, $this->getName())) {
            return $this->getValue();
        }

        if ($data instanceof Model && in_array($this->getName(), $data->getHidden())) {
            return null;
        }

        return Arr::get($data, $this->getName());
    }
}
