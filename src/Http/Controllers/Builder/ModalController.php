<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\ModalNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Requests\Builder\BuildModalRequest;
use Bengr\Admin\Http\Resources\ModalResource;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class ModalController extends Controller
{
    /**
     * Build a modal
     */
    public function build(BuildModalRequest $request)
    {
        $page = BengrAdmin::getPageByUrl($request->get('url'));

        if (!$page) return response()->throw(PageNotFoundException::class);

        $modal = $page->getModal($request->get('modal_id'));

        if (!$modal) return response()->throw(ModalNotFoundException::class);

        return $page->processToResponse($request, fn () => response()->resource(ModalResource::class, $modal));
    }
}
