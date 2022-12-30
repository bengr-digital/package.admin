<?php

namespace Bengr\Admin\Actions\Concerns;

trait CanHandleAction
{

    protected ?\Closure $handleMethod = null;

    public function handle(\Closure $method): static
    {
        $this->handleMethod = $method;

        return $this;
    }

    public function getHandleMethod(): ?\Closure
    {
        return $this->handleMethod;
    }
}
