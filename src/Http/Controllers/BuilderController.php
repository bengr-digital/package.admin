<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\PageResource;
use Illuminate\Http\Request;

use function Bengr\Support\response;

class BuilderController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) {
            return response()->throw(PageNotFoundException::class);
        }

        return $page->processToResponse($request, fn () => response()->resource(PageResource::class, $page));
    }
}
