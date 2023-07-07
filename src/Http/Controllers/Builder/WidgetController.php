<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Requests\Builder\BuildWidgetRequest;
use Bengr\Admin\Http\Resources\WidgetResource;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class WidgetController extends Controller
{
    /**
     * Build a widget
     */
    public function build(BuildWidgetRequest $request)
    {
        $page = Admin::getPageByUrl($request->get('url'));

        if (!$page) throw new PageNotFoundException();

        $widget = $page->getWidget($request->get('widget_id'));

        if (!$widget) throw new WidgetNotFoundException();

        return $page->processToResponse($request, fn () => WidgetResource::make($widget));
    }
}
