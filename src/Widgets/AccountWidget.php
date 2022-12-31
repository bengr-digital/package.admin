<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;

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

    public function getActions(): array
    {
        return [
            Action::make('create')->handle(fn ($payload) => dd($payload))
        ];
    }
}
