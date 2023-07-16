<?php

namespace Bengr\Admin\Exceptions;

use Bengr\Admin\Facades\Admin;

class ActionNotFoundException extends \Exception
{
    public function render($request)
    {
        $dashboard = Admin::getPageByKey('dashboard');

        return response()->json([
            'message' => __('admin.exceptions.action_notfound'),
            'redirect' => $dashboard ? [
                'url' => $dashboard->getRouteUrl(),
                'name' => $dashboard->getRouteName()
            ] : null
        ], 404);
    }
}
