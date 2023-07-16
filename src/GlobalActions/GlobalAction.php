<?php

namespace Bengr\Admin\GlobalActions;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalAction
{
    protected ?string $name = null;

    protected array $middlewares = [];

    public function getName(): string
    {
        return $this->name ?? (string) Str::of(class_basename(static::class))
            ->kebab()
            ->lower();
    }

    public function processMiddleware(int $index, Request $request, \Closure $response)
    {
        $middleware = $this->middlewares[$index];
        $data = [];

        if (!class_exists($this->middlewares[$index])) {
            $parsed = explode(':', $middleware);
            $middleware = array_key_exists($parsed[0], app(Kernel::class)->getRouteMiddleware()) ? app(Kernel::class)->getRouteMiddleware()[$parsed[0]] : null;
            $data = array_splice($parsed, 1);
        }

        if (!$middleware) return $response();

        if ($index === count($this->middlewares) - 1) {
            return app($middleware)->handle($request, $response, ...$data);
        } else {
            return app($middleware)->handle($request, fn () => $this->processMiddleware($index + 1, $request, $response), ...$data);
        }
    }

    public function processToResponse(Request $request, \Closure $response)
    {
        if (!count($this->middlewares)) return $response();

        return $this->processMiddleware(0, $request, $response);
    }

    public function call(array $payload = [])
    {
        return null;
    }
}
