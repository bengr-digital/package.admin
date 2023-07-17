<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\WithParam\WithParamWithColumn;
use Bengr\Admin\Tests\TestCase;
use Bengr\Admin\Tests\Support\TestResources\Pages\WithActions;

class ActionsTest extends TestCase
{

    public function test_creating_action_with_custom_name()
    {
        $action = Action::make('custom-name');

        $this->assertEquals('custom-name', $action->getName());
    }

    public function test_creating_action_with_custom_confirm()
    {
        $action = Action::make()
            ->confirm('confirm title', 'confirm description')
            ->confirmColor('red')
            ->confirmButtons('confirm', 'cancel');

        $this->assertEquals('confirm title', $action->getConfirmTitle());
        $this->assertEquals('confirm description', $action->getConfirmDescription());
        $this->assertEquals('red', $action->getConfirmColor());
        $this->assertEquals('confirm', $action->getConfirmConfirmText());
        $this->assertEquals('cancel', $action->getConfirmCancelText());
    }

    public function test_creating_action_with_custom_label()
    {
        $action = Action::make()
            ->label('Label');

        $this->assertEquals('Label', $action->getLabel());
    }

    public function test_creating_action_with_custom_icon()
    {
        $action = Action::make()
            ->icon('dashboard', 'filled');

        $this->assertEquals('dashboard', $action->getIconName());
        $this->assertEquals('filled', $action->getIconType());
        $this->assertTrue($action->hasIcon());
    }

    public function test_creating_action_with_custom_icon_without_type()
    {
        $action = Action::make()
            ->icon('dashboard');

        $this->assertEquals('dashboard', $action->getIconName());
        $this->assertEquals('outlined', $action->getIconType());
        $this->assertTrue($action->hasIcon());
    }

    public function test_creating_action_with_custom_active_icon()
    {
        $action = Action::make()
            ->activeIcon('dashboard', 'outlined');

        $this->assertEquals('dashboard', $action->getActiveIconName());
        $this->assertEquals('outlined', $action->getActiveIconType());
        $this->assertFalse($action->hasIcon());
    }

    public function test_creating_action_with_custom_active_icon_without_type()
    {
        $action = Action::make()
            ->activeIcon('dashboard');

        $this->assertEquals('dashboard', $action->getActiveIconName());
        $this->assertEquals('filled', $action->getActiveIconType());
        $this->assertFalse($action->hasIcon());
    }

    public function test_creating_action_with_custom_color()
    {
        $action = Action::make()
            ->color('primary');

        $this->assertEquals('primary', $action->getColor());
    }

    public function test_creating_action_with_custom_size()
    {
        $action = Action::make()
            ->size('large');

        $this->assertEquals('large', $action->getSize());
    }

    public function test_creating_action_with_custom_redirect_as_page()
    {
        $action = Action::make()
            ->redirect(WithParamWithColumn::class, [
                'subpage' => 'franta'
            ]);

        $this->assertEquals('/subpages/franta/with-column', $action->getRedirectUrl());
        $this->assertEquals('admin.components.pages.subpages.{subpage:name_code}.with-column', $action->getRedirectName());
        $this->assertEquals(false, $action->openInNewTab());
    }

    public function test_creating_action_with_custom_redirect_as_page_without_params()
    {
        $action = Action::make()
            ->redirect(WithParamWithColumn::class);

        $this->assertEquals('/subpages/{subpage}/with-column', $action->getRedirectUrl());
        $this->assertEquals('admin.components.pages.subpages.{subpage:name_code}.with-column', $action->getRedirectName());
        $this->assertEquals(false, $action->openInNewTab());
    }

    public function test_creating_action_with_custom_redirect_as_url()
    {
        $action = Action::make()
            ->redirect('https://www.bengr.cz');

        $this->assertEquals('https://www.bengr.cz', $action->getRedirectUrl());
        $this->assertEquals(null, $action->getRedirectName());
        $this->assertEquals(false, $action->openInNewTab());
    }

    public function test_creating_action_with_custom_redirect_with_in_new_tab()
    {
        $action = Action::make()
            ->redirect('https://www.bengr.cz')
            ->inNewTab();

        $this->assertEquals('https://www.bengr.cz', $action->getRedirectUrl());
        $this->assertEquals(null, $action->getRedirectName());
        $this->assertEquals(true, $action->openInNewTab());
    }

    public function test_creating_action_with_custom_redirect_with_callback()
    {
        $subpage = Subpage::factory()
            ->count(1)
            ->sequence(
                [
                    'id' => 1
                ]
            )
            ->create()
            ->first();

        $action = Action::make()
            ->redirect(WithParamWithColumn::class, fn (Subpage $record) => [
                'subpage' => $record->id
            ]);

        $this->assertEquals('/subpages/1/with-column', $action->getRedirectUrl(['record' => $subpage]));
        $this->assertEquals('admin.components.pages.subpages.{subpage:name_code}.with-column', $action->getRedirectName(['record' => $subpage]));
        $this->assertEquals(false, $action->openInNewTab());
    }

    public function test_creating_action_with_custom_tooltip()
    {
        $action = Action::make()
            ->tooltip('example tooltip');

        $this->assertEquals('example tooltip', $action->getTooltip());
    }

    public function test_creating_action_with_custom_params()
    {
        $action = Action::make()
            ->params([
                'modal_id' => 12
            ]);

        $this->assertEquals(['modal_id' => 12], $action->getParams());
    }

    public function test_creating_action_with_custom_params_with_record()
    {
        $subpage = Subpage::factory()
            ->count(1)
            ->sequence(
                [
                    'id' => 1
                ]
            )
            ->create()
            ->first();

        $action = Action::make()
            ->params(fn (Subpage $record) => [
                'modal_id' => $record->id
            ]);

        $action->record($subpage);

        $this->assertEquals(['modal_id' => 1], $action->getParams());
    }

    public function test_creating_action_with_custom_type()
    {
        $action = Action::make()
            ->type('submit');

        $this->assertEquals('submit', $action->getType());
    }

    public function test_creating_action_with_custom_disabled()
    {
        $action = Action::make()
            ->disabled();

        $this->assertEquals(true, $action->isDisabled());
    }

    public function test_creating_action_with_custom_hidden()
    {
        $action = Action::make()
            ->hidden();

        $this->assertEquals(true, $action->isHidden());
    }

    public function test_creating_action_with_custom_download()
    {
        $action = Action::make()
            ->download();

        $this->assertEquals(true, $action->isDownload());
    }

    public function test_creating_action_with_custom_record()
    {
        $subpage = Subpage::factory()
            ->count(1)
            ->sequence(
                [
                    'id' => 1
                ]
            )
            ->create()
            ->first();

        $action = Action::make()
            ->record($subpage);

        $this->assertEquals($subpage, $action->getRecord());
    }

    public function test_creating_action_with_custom_modal()
    {
        $action = Action::make()
            ->modal(1);

        $this->assertEquals(1, $action->getModalId());
        $this->assertEquals(null, $action->getModalCodeId());
        $this->assertEquals('open', $action->getModalEvent());
    }

    public function test_creating_action_with_custom_modal_with_codeId()
    {
        $action = Action::make()
            ->modal('create');

        $this->assertEquals(null, $action->getModalId());
        $this->assertEquals('create', $action->getModalCodeId());
        $this->assertEquals('open', $action->getModalEvent());
    }

    public function test_creating_action_with_custom_modal_with_not_specified_codeId()
    {
        $action = Action::make('create_name')
            ->modal();

        $this->assertEquals(null, $action->getModalId());
        $this->assertEquals('create_name', $action->getModalCodeId());
        $this->assertEquals('open', $action->getModalEvent());
    }

    public function test_creating_action_with_custom_modal_with_custom_event()
    {
        $action = Action::make()
            ->modal('create', 'close');

        $this->assertEquals(null, $action->getModalId());
        $this->assertEquals('create', $action->getModalCodeId());
        $this->assertEquals('close', $action->getModalEvent());
    }

    public function test_creating_action_with_custom_handle()
    {
        $action = Action::make()
            ->handle(function () {
                return 'testing return';
            });

        $this->assertEquals(true, $action->hasHandle());
        $this->assertEquals('testing return', $action->getHandleMethod()());
        $this->assertEquals(null, $action->getHandleWidgetId());
    }

    public function test_creating_action_with_custom_handle_with_widget_id()
    {
        $action = Action::make()
            ->handle(function () {
                return 'testing return';
            }, 12);

        $this->assertEquals(true, $action->hasHandle());
        $this->assertEquals('testing return', $action->getHandleMethod()());
        $this->assertEquals(12, $action->getHandleWidgetId());
    }

    public function test_creating_action_with_custom_handle_only_with_widget_id()
    {
        $action = Action::make()
            ->handle(null, 12);

        $this->assertEquals(true, $action->hasHandle());
        $this->assertEquals(null, $action->getHandleMethod());
        $this->assertEquals(12, $action->getHandleWidgetId());
    }

    public function test_creating_action_with_custom_handle_only_with_params()
    {
        $action = Action::make()
            ->handle(function (string $message) {
                return $message;
            }, 12);

        $this->assertEquals(true, $action->hasHandle());
        $this->assertEquals('testing return as param', $action->getHandleMethod()('testing return as param'));
        $this->assertEquals(12, $action->getHandleWidgetId());
    }

    public function test_obtaining_actions_from_simple_page()
    {
        $page = app(WithActions\Simple\Simple::class);

        $this->assertContainsActions(
            [
                [
                    'name' => 'create',
                    'modalId' => null,
                    'modalCodeId' => null,
                    'modalEvent' => 'open',
                    'handleMethodReturn' => 'create action on page',
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'edit',
                    'modalId' => null,
                    'modalCodeId' => 'edit',
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'submit',
                    'modalId' => null,
                    'modalCodeId' => null,
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => null
                ]
            ],
            $page->getActions()
        );
    }

    public function test_obtaining_actions_from_page_with_modals()
    {
        $page = app(WithActions\WithModals\WithModals::class);

        $this->assertContainsActions(
            [
                [
                    'name' => 'create',
                    'modalId' => null,
                    'modalCodeId' => null,
                    'modalEvent' => 'open',
                    'handleMethodReturn' => 'create action on page',
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'edit',
                    'modalId' => 1,
                    'modalCodeId' => 'edit',
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'submit',
                    'modalId' => 2,
                    'modalCodeId' => 'create',
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => null
                ]
            ],
            $page->getTransformedActions()
        );
    }

    public function test_obtaining_actions_from_page_with_widgets()
    {
        $page = app(WithActions\WithWidgets\WithWidgets::class);

        $this->assertContainsActions(
            [
                [
                    'name' => 'create',
                    'modalId' => null,
                    'modalCodeId' => null,
                    'modalEvent' => 'open',
                    'handleMethodReturn' => 'create action on page',
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'edit',
                    'modalId' => null,
                    'modalCodeId' => 'edit',
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => null
                ],
                [
                    'name' => 'submit',
                    'modalId' => null,
                    'modalCodeId' => null,
                    'modalEvent' => 'open',
                    'handleMethodReturn' => null,
                    'handleWidgetId' => null,
                    'type' => 'submit'
                ]
            ],
            $page->getTransformedActions()
        );
    }
}
