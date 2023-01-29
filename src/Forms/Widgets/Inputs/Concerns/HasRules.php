<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasRules
{
    protected array $rules = [];

    public function rules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function getRules(): array
    {
        return [
            $this->getName() => $this->rules
        ];
    }
}
