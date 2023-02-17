<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait CanBeDownload
{
    protected bool | Closure $isDownload = false;

    public function download(bool | Closure $condition = true): static
    {
        $this->isDownload = $condition;

        return $this;
    }

    public function isDownload(): bool
    {
        return $this->evaluate($this->isDownload);
    }
}
