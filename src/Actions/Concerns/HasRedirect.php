<?php

namespace Bengr\Admin\Actions\Concerns;

use Illuminate\Support\Str;

trait HasRedirect
{
    protected ?string $redirectName = null;

    protected ?string $redirectUrl = null;

    public function isParam($value): bool
    {
        return Str::of($value)->startsWith('{') && Str::of($value)->endsWith('}');
    }

    public function redirect(string $page, ?array $params = []): static
    {
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

        $this->redirectUrl = $page->getRouteUrl();
        $this->redirectName = $page->getRouteName();

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    public function getRedirectName(): ?string
    {
        return $this->redirectName;
    }
}
