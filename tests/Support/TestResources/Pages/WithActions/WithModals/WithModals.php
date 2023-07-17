<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\WithActions\WithModals;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Pages\Page;

class WithModals extends Page
{
    public function getModals(): array
    {
        return [
            Modal::make('edit'),
            Modal::make('create')
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
                ->modal('create')
        ];
    }
}
