<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

trait HasRules
{
    protected array | \Closure $rules = [];

    public function rules(array | \Closure $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function getRules(array $parameters = []): array
    {
        return [
            $this->getName() => $this->evaluate($this->rules, $parameters)
        ];
    }
}
