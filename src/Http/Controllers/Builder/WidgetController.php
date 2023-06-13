<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Requests\Builder\BuildWidgetRequest;
use Bengr\Admin\Http\Resources\WidgetResource;

use function Bengr\Support\response;

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
        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) return response()->throw(PageNotFoundException::class);

        $widget = $page->getWidget($request->get('widget_id'));

        if (!$widget) return response()->throw(WidgetNotFoundException::class);

        return $page->processToResponse($request, fn () => response()->resource(WidgetResource::class, $widget));
    }
}
