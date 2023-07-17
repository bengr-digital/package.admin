<?php

namespace Bengr\Admin\Tests\Unit\Widgets\Builtin;

use Bengr\Admin\Tests\TestCase;
use Bengr\Admin\Widgets\CardWidget;
use Bengr\Admin\Widgets\Widget;

class CardWidgetTest extends TestCase
{
    public function test_creating_widget_with_default_properties()
    {
        $widget = CardWidget::make([]);

        $this->assertEquals([], $widget->getWidgets());
        $this->assertEquals(null, $widget->getHeading());
        $this->assertEquals(null, $widget->getSubheading());
        $this->assertEquals([], $widget->getFooter());
    }

    public function test_creating_widget_with_custom_properties()
    {
        $widget = CardWidget::make([
            new Widget(),
            new Widget(),
        ])
            ->heading('testing heading')
            ->subheading('testing subheading')
            ->footer([
                new Widget(),

            ]);

        $this->assertEquals(2, count($widget->getWidgets()));
        $this->assertEquals('testing heading', $widget->getHeading());
        $this->assertEquals('testing subheading', $widget->getSubheading());
        $this->assertEquals(1, count($widget->getFooter()));
    }
}
