<?php

namespace Bengr\Admin\Widgets;

class AccountWidget extends Widget
{
    protected ?string $name = 'account';

    protected int $columnSpan = 6;

    final public function __construct()
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
