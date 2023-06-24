<?php

namespace Bengr\Admin\Pages\Builtin\Auth;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Forms\Form;
use Bengr\Admin\Forms\Widgets\Inputs;
use Bengr\Admin\Pages\Concerns\Translatable;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends Page
{
    use Translatable;

    protected ?string $layout = 'card';

    protected ?string $title = 'admin::pages.login.title';

    protected ?string $description = 'admin::pages.login.description';

    protected ?string $slug = 'auth/login';

    protected bool $inNavigation = false;

    protected bool $hasNavigation = false;

    protected bool $hasTopbar = false;

    protected string | array $middlewares = ['guest:admin'];

    public function getWidgets(): array
    {
        return [
            Widgets\FormWidget::make(BengrAdmin::authUserModel(), $this)
                ->schema([
                    Inputs\Input::make('username')
                        ->label(__('admin::forms.username'))
                        ->placeholder(__('admin::forms.placeholders.email'))
                        ->rules(['required', 'exists:admin_users']),
                    Inputs\Input::make('password')
                        ->label(__('admin::forms.password'))
                        ->placeholder(__('admin::forms.placeholders.password'))
                        ->type('password')->rules(['required']),
                    Widgets\ActionWidget::make('submit')
                        ->label(__('admin::actions.login'))
                        ->color('primary')
                ])
                ->submit(function (Form $form) {
                    $admin = $this->authenticate($form->getValue('username'), $form->getValue('password'));
                    $token = $admin->createToken('bengr-admin-token');

                    return $this->response([
                        'message' => __('admin::auth.success'),
                        'token' => [
                            'name' => $token->getName(),
                            'access_token' => $token->getAccessToken(),
                            'refresh_token' => $token->getRefreshToken(),
                        ]
                    ])->redirect(config('admin.pages.dashboard'));
                })
        ];
    }

    public function authenticate(string $username, string $password): Authenticatable
    {
        $admin = app(BengrAdmin::authUserModel())->where('username', $username)->orWhere('email', $username)->first();

        if (!$admin) {

            throw ValidationException::withMessages([
                'username' => __('admin::auth.failed'),
            ]);
        }

        if (!Hash::check($password, $admin->password)) {

            throw ValidationException::withMessages([
                'username' => __('admin::auth.failed'),
            ]);
        }

        return $admin;
    }
}
