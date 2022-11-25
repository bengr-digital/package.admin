<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Resources\PageResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BuilderController extends Controller
{
    public function __invoke(Request $request)
    {
        $page = Admin::getPage($request->get('name'));

        if (!$page) throw new NotFoundHttpException;

        return PageResource::make($page);
    }
}
