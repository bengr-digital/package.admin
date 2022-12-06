<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\PageResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BuilderController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) throw new NotFoundHttpException;

        ServingAdmin::dispatch();

        return PageResource::make($page);
    }
}
