<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public static $wrap = '';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'route' => [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ],
            'auth' => MeResource::make($request->user('admin')) ?? null,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'layout' => [
                'name' => $this->getLayout(),
                'navigation' => $this->hasNavigation() ? NavigationResource::collection(BengrAdmin::getNavigation()) : null,
                'topbar' => $this->hasTopbar() ? TopbarResource::make($this) : null,
                'header' => HeaderResource::make($this),
                'form' => $this->hasLargeForm() ? WidgetResource::make($this->getLargeForm(), false) : null
            ],
            'content' => [
                'modals' => collect(ModalResource::collection($this->getTransformedModals())->toArray($request))->filter()->values(),
                'widgets' => WidgetResource::collection($this->getTransformedWidgets()),
            ]
        ];
    }
}
