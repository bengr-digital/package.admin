<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\ActionNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Exceptions\GlobalActionNotFoundException;
use Bengr\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if (!$request->get('name')) throw new ActionNotFoundException();

        if (!$request->has('url') && !$request->has('widget_id')) {
            $globalAction = Admin::getGlobalActionByName($request->get('name'));

            if (!$globalAction) throw new GlobalActionNotFoundException();

            return $globalAction->processToResponse($request, fn () => $globalAction->call($request->get('payload') ?? []));
        }

        $page = Admin::getPageByUrl($request->get('url'));

        if (!$page) throw new PageNotFoundException();

        if ($request->get('widget_id')) {
            $widget = $page->getWidget($request->get('widget_id'));


            if (!$widget) throw new WidgetNotFoundException();

            return $page->processToResponse($request, fn () => $widget->callAction($request->get('name'), $request->payload ?? []));
        }

        return $page->processToResponse($request, fn () => $page->callAction($request->get('name'), $request->payload ?? []));
    }
}
