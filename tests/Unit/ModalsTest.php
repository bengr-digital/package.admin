<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Tests\Support\TestResources\Pages\WithModals;
use Bengr\Admin\Tests\TestCase;
use Bengr\Admin\Widgets\CardWidget;

class ModalsTest extends TestCase
{
    public function test_creating_modal_with_default_properties()
    {
        $modal = Modal::make('modal-code-id');

        $this->assertModalEquals(
            modal: $modal,
            id: null,
            codeId: 'modal-code-id',
            type: 'card',
            direction: 'center',
            heading: null,
            subheading: null,
            widgets: [],
            actions: [],
            params: [],
            hasCross: false,
            lazyload: false
        );
    }

    public function test_creating_modal_with_regular_custom_properties()
    {
        $modal = Modal::make('modal-code-id')
            ->heading('custom heading')
            ->subheading('custom subheading')
            ->type('drawer')
            ->cross()
            ->lazyload()
            ->id(12);

        $this->assertModalEquals(
            modal: $modal,
            id: 12,
            codeId: 'modal-code-id',
            type: 'drawer',
            direction: 'right',
            heading: 'custom heading',
            subheading: 'custom subheading',
            widgets: [],
            actions: [],
            params: [],
            hasCross: true,
            lazyload: true
        );
    }

    public function test_creating_modal_with_iregular_custom_properties()
    {
        $modal = Modal::make('modal-code-id')
            ->heading('custom heading')
            ->subheading('custom subheading')
            ->type('custom')
            ->direction('custom-direction')
            ->cross()
            ->lazyload()
            ->id(12);

        $this->assertModalEquals(
            modal: $modal,
            id: 12,
            codeId: 'modal-code-id',
            type: 'custom',
            direction: 'custom-direction',
            heading: 'custom heading',
            subheading: 'custom subheading',
            widgets: [],
            actions: [],
            params: [],
            hasCross: true,
            lazyload: true
        );
    }

    public function test_creating_modal_with_regular_widgets()
    {
        $modal = Modal::make('with-widgets')
            ->widgets([
                CardWidget::make([
                    CardWidget::make([])
                        ->widgetId(10),
                    CardWidget::make([])
                        ->widgetId(20),
                ])
                    ->widgetId(1),
                CardWidget::make([])
                    ->widgetId(2),
                CardWidget::make([])
                    ->widgetId(3),
            ]);

        $this->assertContainsWidgets([
            [
                'class' => CardWidget::class,
                'widgetId' => 1
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 2
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 3
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 10
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 20
            ]
        ], $modal->getWidgets());
    }

    public function test_creating_modal_with_callback_without_params_widgets()
    {
        $modal = Modal::make('with-callback-widgets')
            ->widgets(function () {
                return [
                    CardWidget::make([])
                        ->widgetId(2),
                    CardWidget::make([
                        CardWidget::make([])
                            ->widgetId(20),
                    ])
                        ->widgetId(3)
                ];
            });

        $this->assertContainsWidgets([
            [
                'class' => CardWidget::class,
                'widgetId' => 2
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 3
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 20
            ]
        ], $modal->getWidgets());
    }

    public function test_creating_modal_with_callback_with_params_widgets()
    {
        $modal = Modal::make('with-callback-widgets')
            ->widgets(function (array $params) {
                if (array_key_exists('testing_param', $params) && $params['testing_param']) {
                    return [
                        CardWidget::make([])
                            ->widgetId(1),
                        CardWidget::make([])
                            ->widgetId(2)
                    ];
                } else {
                    return [];
                }
            });

        $this->assertModalEquals(
            modal: $modal,
            id: null,
            codeId: 'with-callback-widgets',
            type: 'card',
            direction: 'center',
            heading: null,
            subheading: null,
            widgets: [],
            actions: [],
            params: [],
            hasCross: false,
            lazyload: false
        );

        $modal->params([
            'testing_param' => true
        ]);

        $this->assertContainsWidgets([
            [
                'class' => CardWidget::class,
                'widgetId' => 1
            ],
            [
                'class' => CardWidget::class,
                'widgetId' => 2
            ]
        ], $modal->getWidgets());
    }

    public function test_creating_modal_with_actions()
    {
        $modal = Modal::make('with-actions')
            ->actions([
                Action::make('create'),
                Action::make('edit')
            ]);

        $this->assertContainsActions([
            [
                'name' => 'create',
                'modalId' => null,
                'modalCodeId' => null,
                'modalEvent' => 'open',
                'handleMethodReturn' => null,
                'handleWidgetId' => null,
                'type' => null
            ],
            [
                'name' => 'edit',
                'modalId' => null,
                'modalCodeId' => null,
                'modalEvent' => 'open',
                'handleMethodReturn' => null,
                'handleWidgetId' => null,
                'type' => null
            ]
        ], $modal->getActions());
    }

    public function test_obtaining_regular_modals_from_page()
    {
        $page = app(WithModals\WithModals::class);

        $this->assertContainsModals([
            [
                'codeId' => 'create',
                'id' => null
            ],
            [
                'codeId' => 'edit',
                'id' => 1
            ],
            [
                'codeId' => 'history',
                'id' => 12
            ],
            [
                'codeId' => 'delete',
                'id' => null
            ]
        ], $page->getModals());
    }

    public function test_obtaining_transformed_modals_from_page()
    {
        $page = app(WithModals\WithModals::class);

        $this->assertContainsModals([
            [
                'codeId' => 'create',
                'id' => 13
            ],
            [
                'codeId' => 'edit',
                'id' => 1
            ],
            [
                'codeId' => 'history',
                'id' => 12
            ],
            [
                'codeId' => 'delete',
                'id' => 14
            ]
        ], $page->getTransformedModals());
    }
}
