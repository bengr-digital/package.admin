<?php

namespace Bengr\Admin\Pages\Builtin;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Pages\Concerns\Translatable;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\ActionCardWidget;

class Dashboard extends Page
{
    use Translatable;

    protected ?string $title = 'admin::pages.dashboard.title';

    protected ?string $description = 'admin::pages.dashboard.description';

    protected ?string $slug = '';

    protected ?string $navigationIconName = 'dashboard';

    protected string | array $middlewares = ['auth:admin'];

    public function getWidgets(): array
    {
        $loggedInUser = BengrAdmin::auth()->user();

        return [
            ActionCardWidget::make(__('admin::texts.welcome') . ', ' . $loggedInUser->first_name . ' ' . $loggedInUser->last_name, $loggedInUser->email)
                ->image($loggedInUser->getFirstMediaUrl('avatar'))
                ->actionOnClick(Action::make()->redirect(config('admin.pages.me')))
                ->columnSpan(4),
            ActionCardWidget::make('Přizpůsobení administrace na míru', 'V případě potřeby vám celou administraci i nástěnku přizpůsobíme vaším potřebám.')
                ->icon('info')
                ->columnSpan(8),
        ];
    }

    public function getActions(): array
    {
        return [];
    }
}
