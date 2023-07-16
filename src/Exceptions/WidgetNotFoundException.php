<?php

namespace Bengr\Admin\Exceptions;

use Bengr\Admin\Facades\Admin;

class WidgetNotFoundException extends \Exception
{
    public function render($request)
    {
        $dashboard = Admin::getPageByKey('dashboard');

        return response()->json([
            'message' => __('admin.exceptions.widget_notfound'),
            'redirect' => $dashboard ? [
                'url' => $dashboard->getRouteUrl(),
                'name' => $dashboard->getRouteName()
            ] : null
        ], 404);
    }
}
