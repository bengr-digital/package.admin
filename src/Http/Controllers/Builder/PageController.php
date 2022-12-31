<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Requests\Builder\BuildPageRequest;
use Bengr\Admin\Http\Resources\PageResource;

use function Bengr\Support\response;

class PageController extends Controller
{
    public function build(BuildPageRequest $request)
    {

        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) return response()->throw(PageNotFoundException::class);

        return $page->processToResponse($request, fn () => response()->resource(PageResource::class, $page));
    }
}
