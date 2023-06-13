<?php

namespace Bengr\Admin\Actions\Concerns;

use Illuminate\Support\Str;

trait HasRedirect
{
    protected string | \Closure | null $redirectPage = null;

    protected array | \Closure | null $redirectParams = null;

    protected ?string $redirectName = null;

    protected ?string $redirectUrl = null;

    protected bool $inNewTab = false;

    public function redirect(string | \Closure | null $page, array | \Closure | null $params = []): static
    {
        $this->redirectPage = $page;
        $this->redirectParams = $params;

        return $this;
    }

    public function inNewTab(bool $condition = true)
    {
        $this->inNewTab = $condition;

        return $this;
    }


    public function isParam($value): bool
    {
        return Str::of($value)->startsWith('{') && Str::of($value)->endsWith('}');
    }

    public function evaluateRedirect(...$parameters)
    {
        return;
    }

    public function getRedirectUrl($parameters = []): ?string
    {
        if (!class_exists($this->redirectPage)) return $this->redirectPage;

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

    public function getRedirectName($parameters = []): ?string
    {
        if (!class_exists($this->redirectPage)) return null;

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

    public function openInNewTab(): bool
    {
        return $this->inNewTab;
    }
}
