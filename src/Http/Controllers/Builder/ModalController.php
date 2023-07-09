<?php

namespace Bengr\Admin\Http\Controllers\Builder;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Exceptions\ModalNotFoundException;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Requests\Builder\BuildModalRequest;
use Bengr\Admin\Http\Resources\ModalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Bengr Administration
 * @subgroup Builder
 */
class ModalController extends Controller
{
    /**
     * Build a modal
     */
    public function build(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => ['required', new \Bengr\Admin\Rules\ValidPageUrl()],
            'modal_id' => ['required', 'int', new \Bengr\Admin\Rules\ValidModalId()],
            'params' => ['nullable', 'array']
        ])->validate();

        $page = Admin::getPageByUrl($validator['url']);
        $modal = $page->getModal($validator['modal_id']);
        $modal->params($validator['params'] ?? []);

        return $page->processToResponse($request, fn () => ModalResource::make($modal));
    }
}
