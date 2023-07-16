<?php

namespace Bengr\Admin\Actions\Concerns;

use Bengr\Support\Url\UrlHolder;
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

    public function getRedirectUrl($parameters = []): ?string
    {
        if (!class_exists($this->redirectPage)) return $this->redirectPage;


        $page = $this->evaluate($this->redirectPage, $parameters);
        $params = $this->evaluate($this->redirectParams, $parameters);

        if ($page) {
            $page = app($page);
            $url = new UrlHolder($page->getSlug());

            collect($url->compileRoute()->getVariables())->each(function ($variable) use ($url, $params) {
                if (key_exists($variable, $params) && $params[$variable] != null) {
                    $url->setUrl(
                        Str::of($url->getUrl())->replace("{{$variable}}", (string) $params[$variable])
                    );
                }
            });

            return '/' . $url->getUrl();
        }

        return $this->redirectUrl ?? null;
    }

    public function getRedirectName($parameters = []): ?string
    {
        if (!class_exists($this->redirectPage)) return null;

        $page = $this->evaluate($this->redirectPage, $parameters);
        $params = $this->evaluate($this->redirectParams, $parameters);

        if ($page) {
            $page = app($page);

            return $page->getRouteName();
        }

        return $this->redirectName ?? null;
    }

    public function openInNewTab(): bool
    {
        return $this->inNewTab;
    }
}
