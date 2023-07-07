<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\ModalNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Requests\Builder\BuildModalRequest;
use Bengr\Admin\Http\Resources\ModalResource;

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
        $page = Admin::getPageByUrl($request->get('url'));

        if (!$page) throw new PageNotFoundException();

        $modal = $page->getModal($request->get('modal_id'));

        if (!$modal) throw new ModalNotFoundException();

        $modal->params($request->get('params') ?? []);

        return $page->processToResponse($request, fn () => ModalResource::make($modal));
    }
}
