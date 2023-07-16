<?php

namespace Bengr\Admin\Tests\Support\TestResources\GlobalActions\Calling;

use Bengr\Admin\GlobalActions\GlobalAction;

class CallingGlobalAction extends GlobalAction
{
    protected ?string $name = 'calling-global-action';

    public function call(array $payload = [])
    {
        return 'action was performed';
    }
}
