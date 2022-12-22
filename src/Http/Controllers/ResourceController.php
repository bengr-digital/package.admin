<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\RecordsResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Resources
 */
class ResourceController extends Controller
{
    /**
     * Get resources
     * 
     * @responseFile status=200 scenario="success" storage/responses/bengr_admin/resources.get.json
     * @responseFile status=200 scenario="logged in" storage/responses/bengr_admin/already_loggedin.json
     * @responseFile status=403 scenario="not logged in" storage/responses/bengr_admin/unauthenticated.json
     * @responseFile status=404 scenario="not found" storage/responses/bengr_admin/notfound.json
     */
    public function get(Request $request)
    {

        $page = BengrAdmin::getPageByName($request->get('name'));

        if (!$page || !$page->hasTable()) return response()->throw(NotFoundHttpException::class);


        return $page->processToResponse($request, fn () => response()->resource(RecordsResource::class, $page));
    }
}
