<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Resources\PageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class PageController extends Controller
{
    /**
     * Build a page
     */
    public function build(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => ['required', new \Bengr\Admin\Rules\ValidPageUrl()],
        ])->validate();

        $page = Admin::getPageByUrl($validator['url']);

        return $page->processToResponse($request, fn () => PageResource::make($page));
    }
}
