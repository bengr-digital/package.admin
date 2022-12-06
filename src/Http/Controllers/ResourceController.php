<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\PageResource;
use Bengr\Admin\Http\Resources\RecordsResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceController extends Controller
{
    public function get(Request $request)
    {
        $page = BengrAdmin::getPageByName($request->get('name'));

        if (!$page || !$page->hasTable()) throw new NotFoundHttpException;

        return RecordsResource::make($page);
    }

    public function post(Request $request)
    {
        return null;
    }
}
