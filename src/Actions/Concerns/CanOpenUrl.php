<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait CanOpenUrl
{
    protected string | Closure | null $url = null;

    public function url(string | Closure | null $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->evaluate($this->url);
    }
}
