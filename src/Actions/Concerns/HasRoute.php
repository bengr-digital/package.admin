<?php

namespace Bengr\Admin\Actions\Concerns;

use Closure;

trait HasRoute
{
    protected string | Closure | null $routeName = null;

    protected string | Closure | null $routeUrl = null;

    public function route(string | Closure | null $routeName, string | Closure | null $routeUrl): static
    {
        $this->routeName = $routeName;
        $this->routeUrl = $routeUrl;

        return $this;
    }

    public function getRouteName(): ?string
    {
        return $this->evaluate($this->routeName);
    }

    public function getRouteUrl(): ?string
    {
        return $this->evaluate($this->routeUrl);
    }
}
