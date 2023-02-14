<?php

namespace Bengr\Admin\Actions\Concerns;

use Illuminate\Support\Str;

trait HasRedirect
{
    protected string | \Closure | null $redirectPage = null;

    protected array | \Closure | null $redirectParams = null;

    protected ?string $redirectName = null;

    protected ?string $redirectUrl = null;

    public function isParam($value): bool
    {
        return Str::of($value)->startsWith('{') && Str::of($value)->endsWith('}');
    }

    public function redirect(string | \Closure | null $page, array | \Closure | null $params = []): static
    {
        $this->redirectPage = $page;
        $this->redirectParams = $params;

        return $this;
    }

    public function evaluateRedirect(...$parameters)
    {
        return;
    }

    public function getRedirectUrl($parameters): ?string
    {
        $page = $this->evaluate($this->redirectPage, $parameters);
        $params = $this->evaluate($this->redirectParams, $parameters);
        if ($page) {
            $url = collect(Str::of(app($page)->getRouteUrl())->explode("/")->filter()->values())->map(function ($part) use ($params) {
                if ($this->isParam($part)) {
                    $param = null;

                    $parsed_param = Str::of($part)->replace('{', '')->replace('}', '')->explode(':');

                    if ($parsed_param->count() == 2) {
                        $param = $parsed_param[1];
                    } else {
                        $param = $parsed_param[0];
                    }

                    return array_key_exists($param, $params) ? $params[$param] : $part;
                }
                return $part;
            })->prepend('')->join('/');

            $page = app($page)->slug($url)->params($params);

            return $page->getRouteUrl();
        }

        return $this->redirectUrl ?? null;
    }

    public function getRedirectName($parameters): ?string
    {
        $page = $this->evaluate($this->redirectPage, $parameters);
        $params = $this->evaluate($this->redirectParams, $parameters);

        if ($page) {
            $url = collect(Str::of(app($page)->getRouteUrl())->explode("/")->filter()->values())->map(function ($part) use ($params) {
                if ($this->isParam($part)) {
                    $param = null;

                    $parsed_param = Str::of($part)->replace('{', '')->replace('}', '')->explode(':');

                    if ($parsed_param->count() == 2) {
                        $param = $parsed_param[1];
                    } else {
                        $param = $parsed_param[0];
                    }

                    return array_key_exists($param, $params) ? $params[$param] : $part;
                }
                return $part;
            })->prepend('')->join('/');

            $page = app($page)->slug($url)->params($params);

            return $page->getRouteName();
        }

        return $this->redirectName ?? null;
    }
}
