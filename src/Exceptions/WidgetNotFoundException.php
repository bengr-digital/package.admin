<?php

namespace Bengr\Admin\Exceptions;

use Bengr\Admin\Facades\Admin as BengrAdmin;

use function Bengr\Support\response;

class WidgetNotFoundException extends \Exception
{
    public function render($request)
    {
        $dashboard = BengrAdmin::dashboardPage();

        return response()->json([
            'message' => __('admin.exceptions.widget_notfound'),
            'redirect' => $dashboard ? [
                'url' => $dashboard->getRouteUrl(),
                'name' => $dashboard->getRouteName()
            ] : null
        ])->status(404);
    }
}
