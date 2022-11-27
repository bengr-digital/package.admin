<?php

namespace Bengr\Admin\Tables;

class Table
{
    final public function __construct()
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
