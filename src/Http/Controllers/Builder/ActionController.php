<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Requests\Builder\CallActionRequest;

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
    public function call(CallActionRequest $request)
    {

        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) return response()->throw(PageNotFoundException::class);

        if ($request->has('widget_id')) {
            $widget = $page->getWidget($request->get('widget_id'));


            if (!$widget) return response()->throw(WidgetNotFoundException::class);

            return $page->processToResponse($request, fn () => $widget->callAction($request->get('name'), $request->get('payload', [])));
        }

        return $page->processToResponse($request, fn () => $page->callAction($request->get('name'), $request->get('payload', [])));
    }
}
