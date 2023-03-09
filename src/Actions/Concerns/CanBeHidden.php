<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait CanBeHidden
{
    protected bool | Closure $isHidden = false;

    protected bool | Closure $isVisible = true;

    public function hidden(bool | Closure $condition = true): static
    {
        $this->isHidden = $condition;

        return $this;
    }

    public function visible(bool | Closure $condition = true): static
    {
        $this->isVisible = $condition;

        return $this;
    }

    public function isHidden($parameters = []): bool
    {
        if ($this->evaluate($this->isHidden, $parameters)) {
            return true;
        }

        if (!$this->evaluate($this->isVisible, $parameters)) {
            return true;
        }

        return false;
    }
}
