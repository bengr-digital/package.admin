<?php

namespace Bengr\Admin\Pages;

class PageRoute
{
    protected string $name;

    protected string $uri;

    final public function __construct(string $name, string $uri)
    {
        $this->name($name);
        $this->uri($uri);
    }

    public static function make(string $name, string $uri): static
    {
        return app(static::class, ['name' => $name, 'uri' => $uri]);
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function uri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
