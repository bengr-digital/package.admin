<?php

namespace Bengr\Admin\Actions\Concerns;

trait HasParams
{
    protected array | \Closure $params = [];

    public function params(array | \Closure $params = []): static
    {
        $this->params = $params;

        return $this;
    }

    public function getParams(): array
    {
        return $this->evaluate($this->params, ['record' => $this->getRecord()]);
    }
}
