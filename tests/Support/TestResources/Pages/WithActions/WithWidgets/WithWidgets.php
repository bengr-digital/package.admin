<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\WithActions\WithWidgets;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Pages\Page;
use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Bengr\Admin\Widgets\FormWidget;

class WithWidgets extends Page
{
    public function getWidgets(): array
    {
        return [
            FormWidget::make(Subpage::class)
                ->submit(function () {
                })
        ];
    }

    public function getActions(): array
    {
        return [
            Action::make('create')
                ->handle(function () {
                    return 'create action on page';
                }),
            Action::make('edit')
                ->modal(),
            Action::make('submit')
        ];
    }
}
