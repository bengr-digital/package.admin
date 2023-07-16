<?php

namespace Bengr\Admin\Tests\Unit\Widgets;

use Bengr\Admin\Tests\TestCase;
use Bengr\Admin\Widgets\Widget;
use Bengr\Admin\Tests\Support\TestResources\Pages\WithWidgets;

class WidgetsTest extends TestCase
{
    public function test_creating_widget_with_default_properties()
    {
        $widget = new Widget();

        $this->assertWidgetEquals(
            widget: $widget,
            id: null,
            name: 'widget',
            columnSpan: 12,
            sort: 0,
            lazyload: false
        );
    }

    public function test_creating_widget_with_custom_properties()
    {
        $widget = (new Widget())
            ->columnSpan(6)
            ->lazyload()
            ->widgetId(12);

        $this->assertWidgetEquals(
            widget: $widget,
            id: 12,
            name: 'widget',
            columnSpan: 6,
            sort: 0,
            lazyload: true
        );
    }

    public function test_obtaining_regular_widgets_from_page()
    {
        $page = app(WithWidgets\WithWidgets::class);

        $this->assertContainsWidgets([
            [
                'class' => Widget::class,
                'widgetId' => null
            ],
            [
                'class' => Widget::class,
                'widgetId' => 1
            ],
            [
                'class' => Widget::class,
                'widgetId' => 12
            ],
            [
                'class' => Widget::class,
                'widgetId' => null
            ]
        ], $page->getWidgets());
    }

    public function test_obtaining_transformed_widgets_from_page()
    {
        $page = app(WithWidgets\WithWidgets::class);

        $this->assertContainsWidgets([
            [
                'class' => Widget::class,
                'widgetId' => 13
            ],
            [
                'class' => Widget::class,
                'widgetId' => 1
            ],
            [
                'class' => Widget::class,
                'widgetId' => 12
            ],
            [
                'class' => Widget::class,
                'widgetId' => 14
            ]
        ], $page->getTransformedWidgets());
    }
}
