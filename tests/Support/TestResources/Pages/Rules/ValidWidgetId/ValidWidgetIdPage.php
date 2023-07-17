<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\Rules\ValidWidgetId;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\CardWidget;

class ValidWidgetIdPage extends Page
{
    protected ?string $slug = '/valid-widget-id';

    public function getWidgets(): array
    {
        return [
            CardWidget::make([])
                ->widgetId(69)
        ];
    }
}
