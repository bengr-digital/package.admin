<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\WithWidgets;

use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\Widget;

class WithWidgets extends Page
{
    public function getWidgets(): array
    {
        return [
            new Widget(),
            (new Widget())->widgetId(1),
            (new Widget())->widgetId(12),
            new Widget(),
        ];
    }
}
