<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin;
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
            'layout' => $this->getLayout(),
            'route' => [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ],
            'navigation' => NavigationResource::make(Admin::getNavigation()),
            'topbar' => [
                'visible' => true,
                'userMenu' => UserMenuResource::make(Admin::getUserMenuItems()),
                'notifications' => [
                    'visible' => false
                ],
                'globalSearch' => [
                    'visible' => false
                ]
            ],
            'header' => [
                'heading' => $this->getTitle(),
                'subheading' => $this->getDescription(),
                'actions' => ActionGroupResource::collection($this->getActions())
            ],
            'widgets' => WidgetResource::collection($this->getWidgets()),
            'table' => $this->hasTable() ? TableResource::make($this->getTable($request)) : []
        ];
    }
}
