<?php

namespace Bengr\Admin\Widgets;

use Bengr\Admin\Actions\Action;

class AccountWidget extends Widget
{
    protected ?string $widgetName = 'account';

    protected ?int $widgetColumnSpan = 6;

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
