<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\WithActions\Simple;

use Bengr\Admin\Actions\Action;
use Bengr\Admin\Pages\Page;

class Simple extends Page
{
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
