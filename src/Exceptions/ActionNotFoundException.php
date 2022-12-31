<?php

namespace Bengr\Admin\Exceptions;

use Bengr\Admin\Facades\Admin as BengrAdmin;

use function Bengr\Support\response;

class ActionNotFoundException extends \Exception
{
    public function render($request)
    {
        $dashboard = BengrAdmin::dashboardPage();

        return response()->json([
            'message' => 'Action not found',
            'redirect' => $dashboard ? [
                'url' => $dashboard->getRouteUrl(),
                'name' => $dashboard->getRouteName()
            ] : null
        ])->status(404);
    }
}
