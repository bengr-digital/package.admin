<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Requests\Builder\BuildPageRequest;
use Bengr\Admin\Http\Resources\PageResource;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class PageController extends Controller
{
    /**
     * Build a page
     */
    public function build(BuildPageRequest $request)
    {
        $page = Admin::getPageByUrl($request->get('url'));

        if (!$page) throw new PageNotFoundException();

        return $page->processToResponse($request, fn () => PageResource::make($page));
    }
}
