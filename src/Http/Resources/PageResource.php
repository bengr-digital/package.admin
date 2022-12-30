<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
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
            'auth' => $request->user('admin') ?? null,
            'breadcrumbs' => $this->getBreadcrumbs(),
            'layout' => [
                'name' => $this->getLayout(),
                'navigation' => $this->hasNavigation() ? NavigationResource::collection(BengrAdmin::getNavigation()) : null,
                'topbar' => $this->hasTopbar() ? [
                    'userMenu' => UserMenuResource::make(BengrAdmin::getUserMenuItems()),
                    'notifications' => null,
                    'globalSearch' => null
                ] : [],
                'header' => [
                    'heading' => $this->getTitle(),
                    'subheading' => $this->getDescription(),
                    'actions' => ActionGroupResource::collection($this->getActions())
                ],
            ],
            'content' => [
                'modals' => ModalResource::collection($this->getModals()),
                'widgets' => WidgetResource::collection($this->getWidgets()),
            ]
        ];
    }
}
