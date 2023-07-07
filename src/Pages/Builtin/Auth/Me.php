<?php

namespace Bengr\Admin\Pages\Builtin\Auth;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Forms\Form;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets;
use Bengr\Admin\Forms\Widgets\Inputs;
use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Pages\Concerns\Translatable;
use Illuminate\Validation\Rule;

class Me extends Page
{
    use Translatable;

    protected ?string $title = 'admin::pages.me.title';

    protected ?string $description = 'admin::pages.me.description';

    protected ?string $slug = 'auth/me';

    protected bool $inNavigation = false;

    protected bool $hasNavigation = false;

    protected string | array $middlewares = ['auth:admin'];

    public function getWidgets(): array
    {
        return [
            Widgets\FormWidget::make(Admin::getAuthUserModel(), $this)
                ->record(Admin::auth()->user())
                ->schema([
                    Widgets\BoxWidget::make([
                        Widgets\CardWidget::make([
                            Inputs\Input::make('first_name')
                                ->label(__('admin::forms.first_name'))
                                ->rules(['required'])
                                ->columnSpan(6),
                            Inputs\Input::make('last_name')
                                ->label(__('admin::forms.last_name'))
                                ->rules(['required'])
                                ->columnSpan(6),
                            Inputs\Input::make('username')
                                ->label(__('admin::forms.username'))
                                ->rules(['required', Rule::unique('admin_users')->ignore(Admin::auth()->id())]),
                            Inputs\Input::make('email')
                                ->label(__('admin::forms.email'))
                                ->placeholder(__('admin::forms.placeholders.email'))
                                ->rules(['required', Rule::unique('admin_users')->ignore(Admin::auth()->id())]),
                        ])->heading(__('admin::texts.general_settings')),
                        Widgets\CardWidget::make([
                            Inputs\Input::make('password')
                                ->label(__('admin::forms.password'))
                                ->placeholder(__('admin::forms.placeholders.password'))
                                ->type('password')
                                ->rules(['nullable', 'current_password:admin']),
                            Inputs\Input::make('password_new')
                                ->label(__('admin::forms.password_new'))
                                ->placeholder(__('admin::forms.placeholders.password'))
                                ->type('password')
                                ->rules(['required_with:password']),
                            Inputs\Input::make('password_new_confirmation')
                                ->label(__('admin::forms.password_new_confirmation'))
                                ->placeholder(__('admin::forms.placeholders.password'))
                                ->type('password')
                                ->rules(['required_with:password', 'same:password_new']),
                        ])->heading(__('admin::texts.password'))
                    ])->columnSpan(8),
                    Widgets\BoxWidget::make([
                        Widgets\CardWidget::make([
                            Inputs\AvatarInput::make('avatar')
                                ->rules(['nullable', Rule::bengrFile(), Rule::bengrFileMax(1024), Rule::bengrFileMime(['jpg', 'png', 'svg', 'jpeg'])])
                        ])
                    ])->columnSpan(4)
                ])
                ->submit(function (Form $form) {
                    $form->save(['password', 'password_new_confirmation', !$form->getValue('password_new') ? 'password_new' : ''], ['password_new' => 'password']);

                    return $this->response(__('admin::states.saved'))->redirect(Admin::getPageByKey('me'));
                })

        ];
    }

    public function getModals(): array
    {
        return [
            Modal::make('create')
                ->heading(__('admin::modals.create.title'))
                ->widgets([
                    Inputs\Input::make('name')
                        ->label('gej')
                ]),
            Modal::make('create2')
                ->heading(__('admin::modals.create.title'))
                ->lazyload()
                ->widgets([
                    Inputs\Input::make('name')
                        ->label('gej')
                ])
        ];
    }

    public function getActions(): array
    {
        return [
            Action::make('submit')
                ->label(__('admin::actions.save'))
                ->icon('edit', 'outlined')
                ->color('primary')
        ];
    }
}
