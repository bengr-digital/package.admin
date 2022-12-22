<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Resources\PageResource;
use Illuminate\Http\Request;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class BuilderController extends Controller
{
    /**
     * Build page
     * 
     * @responseFile status=200 scenario="success" storage/responses/bengr_admin/builder.get.json
     * @responseFile status=200 scenario="logged in" storage/responses/bengr_admin/already_loggedin.json
     * @responseFile status=403 scenario="not logged in" storage/responses/bengr_admin/unauthenticated.json
     * @responseFile status=404 scenario="not found" storage/responses/bengr_admin/notfound.json
     */
    public function __invoke(Request $request)
    {
        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) {
            return response()->throw(PageNotFoundException::class);
        }

        return $page->processToResponse($request, fn () => response()->resource(PageResource::class, $page));
    }
}
