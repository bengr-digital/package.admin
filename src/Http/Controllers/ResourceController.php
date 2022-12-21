<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\RecordsResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Bengr\Support\response;

class ResourceController extends Controller
{
    public function get(Request $request)
    {

        $page = BengrAdmin::getPageByName($request->get('name'));

        if (!$page || !$page->hasTable()) return response()->throw(NotFoundHttpException::class);


        return $page->processToResponse($request, fn () => response()->resource(RecordsResource::class, $page));
    }
}
