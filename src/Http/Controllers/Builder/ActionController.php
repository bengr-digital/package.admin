<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Exceptions\GlobalActionNotFoundException;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Illuminate\Http\Request;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class ActionController extends Controller
{
    /**
     * Call action on page or widget
     */
    public function call(Request $request)
    {
        if (!$request->get('name')) return response()->throw(ActionNotFoundException::class);

        if (!$request->has('url') && !$request->has('widget_id')) {
            $globalAction = BengrAdmin::getGlobalActionByName($request->get('name'));

            if (!$globalAction) return response()->throw(GlobalActionNotFoundException::class);

            return $globalAction->processToResponse($request, fn () => $globalAction->call($request->get('payload') ?? []));
        }

        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) return response()->throw(PageNotFoundException::class);

        if ($request->get('widget_id')) {
            $widget = $page->getWidget($request->get('widget_id'));


            if (!$widget) return response()->throw(WidgetNotFoundException::class);

            return $page->processToResponse($request, fn () => $widget->callAction($request->get('name'), $request->payload ?? []));
        }

        return $page->processToResponse($request, fn () => $page->callAction($request->get('name'), $request->payload ?? []));
    }
}
