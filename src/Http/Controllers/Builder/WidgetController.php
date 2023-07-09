<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Resources\WidgetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class WidgetController extends Controller
{
    /**
     * Build a widget
     */
    public function build(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => ['required', new \Bengr\Admin\Rules\ValidPageUrl()],
            'widget_id' => ['required', 'int', new \Bengr\Admin\Rules\ValidWidgetId()]
        ])->validate();

        $page = Admin::getPageByUrl($validator['url']);
        $widget = $page->getWidget($validator['widget_id']);

        return $page->processToResponse($request, fn () => WidgetResource::make($widget));
    }
}
