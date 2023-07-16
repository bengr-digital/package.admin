<?php

namespace Bengr\Admin\Exceptions;

use Bengr\Admin\Facades\Admin;

class GlobalActionNotFoundException extends \Exception
{
    public function render($request)
    {
        $dashboard = Admin::getPageByKey('dashboard');

        return response()->json([
            'message' => __('admin.exceptions.global_action_notfound'),
            'redirect' => $dashboard ? [
                'url' => $dashboard->getRouteUrl(),
                'name' => $dashboard->getRouteName()
            ] : null
        ], 404);
    }
}
