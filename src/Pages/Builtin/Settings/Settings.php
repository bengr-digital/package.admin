<?php

namespace Bengr\Admin\Pages\Builtin\Settings;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Forms\Form;
use Bengr\Admin\Models\AdminSettings;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Widgets\BoxWidget;
use Bengr\Admin\Widgets\CardWidget;
use Bengr\Admin\Widgets\FormWidget;
use Bengr\Admin\Forms\Widgets\Inputs;
use Bengr\Admin\Pages\Concerns\Translatable;
use Bengr\Localization\Facades\Localization;

class Settings extends Page
{
    use Translatable;

    protected ?int $navigationSort = 100;

    protected ?string $title = 'admin::pages.settings.title';

    protected ?string $description = 'admin::pages.settings.description';

    protected ?string $slug = 'settings';

    protected ?string $navigationGroup = '#';

    protected ?string $navigationIconName = 'settings';

    protected string | array $middlewares = ['auth:admin'];

    protected bool $hasLargeForm = true;

    public function getWidgets(): array
    {
        return [
            FormWidget::make(AdminSettings::class, $this)
                ->record(AdminSettings::first() ?? AdminSettings::create())
                ->schema([
                    BoxWidget::make([
                        CardWidget::make([
                            Inputs\Input::make('billing.name')
                                ->label(__('admin::forms.company_name'))
                                ->placeholder(__('admin::forms.placeholders.company_name'))
                                ->columnSpan(12),
                            Inputs\Input::make('billing.cin')
                                ->label(__('admin::forms.cin'))
                                ->placeholder(__('admin::forms.placeholders.cin'))
                                ->columnSpan(6),
                            Inputs\Input::make('billing.tin')
                                ->label(__('admin::forms.tin'))
                                ->placeholder(__('admin::forms.placeholders.tin'))
                                ->columnSpan(6),
                            Inputs\Input::make('billing.city')
                                ->label(__('admin::forms.city'))
                                ->columnSpan(6),
                            Inputs\Input::make('billing.street')
                                ->label(__('admin::forms.street'))
                                ->columnSpan(6),
                            Inputs\Input::make('billing.zipcode')
                                ->label(__('admin::forms.zipcode'))
                                ->columnSpan(6),
                            Inputs\Select::make('billing.country')
                                ->label(__('admin::forms.country'))
                                ->options(Localization::countries()->all())->rules([])
                                ->columnSpan(6),
                        ])->heading(__('admin::texts.billing_information')),
                        CardWidget::make([
                            Inputs\Input::make('phone')
                                ->label(__('admin::forms.phone'))
                                ->placeholder(__('admin::forms.placeholders.phone')),
                            Inputs\Input::make('email')
                                ->label(__('admin::forms.email'))
                                ->placeholder(__('admin::forms.placeholders.email'))
                                ->rules(['nullable', 'email']),
                        ])->heading(__('admin::texts.contact'))
                    ])->columnSpan(7)
                ])
                ->submit(function (Form $form) {
                    $form->save();

                    return $this->response(__('admin::states.saved'))->redirect(config('admin.pages.settings'));
                })
        ];
    }

    public function getActions(): array
    {
        return [
            Action::make('submit')
                ->label(__('admin::actions.save'))
                ->icon('edit')
                ->color('primary')
        ];
    }
}
