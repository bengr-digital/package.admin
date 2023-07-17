<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\Rules\ValidModalId;

use Bengr\Admin\Modals\Modal;
use Bengr\Admin\Pages\Page;

class ValidModalIdPage extends Page
{
    protected ?string $slug = '/valid-modal-id';

    public function getModals(): array
    {
        return [
            Modal::make('testing')
                ->id(69)
        ];
    }
}
