<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\WithModals;

use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Pages\Page;

class WithModals extends Page
{
    public function getModals(): array
    {
        return [
            Modal::make('create'),
            Modal::make('edit')
                ->id(1),
            Modal::make('history')
                ->id(12),
            Modal::make('delete')
        ];
    }
}
