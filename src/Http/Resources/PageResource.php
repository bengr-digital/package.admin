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
            'table' => [
                'resource' => 'admins',
                'filters' => [],
                'columns' => [
                    [
                        'name' => 'username',
                        'type' => 'text',
                        'label' => 'Přihlašovací jméno',
                        'isSortable' => true
                    ],
                    [
                        'name' => 'first_name',
                        'type' => 'text',
                        'label' => 'Jméno',
                        'isSortable' => false
                    ],
                    [
                        'name' => 'last_name',
                        'type' => 'text',
                        'label' => 'Přijmení',
                        'isSortable' => false
                    ],
                    [
                        'name' => 'email',
                        'type' => 'text',
                        'label' => 'E-mail',
                        'isSortable' => true
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'text',
                        'label' => 'Vytvořeno',
                        'isSortable' => true
                    ]
                ],
                'bulkActions' => [
                    [
                        'label' => 'Delete',
                        'icon' => 'trash',
                        'size' => null,
                        'tooltip' => null,
                        'isDisabled' => false,
                        'isHidden' => false,
                        'route' => [
                            'name' => null,
                            'url' => null
                        ]
                    ]
                ],
                'records' => [
                    [
                        'id' => 2,
                        'username' => 'matejkrenek',
                        'first_name' => 'matej',
                        'last_name' => 'krenek',
                        'email' => null,
                        'created_at' => null,
                        'actions' => [
                            [
                                'label' => 'View',
                                'icon' => 'eye',
                                'size' => null,
                                'tooltip' => null,
                                'isDisabled' => false,
                                'isHidden' => false,
                                'route' => [
                                    'name' => 'admin.pages.admins.detail',
                                    'url' => '/admins/2'
                                ]
                            ]
                        ]
                    ],
                    [
                        'id' => 3,
                        'username' => 'admin',
                        'first_name' => 'admin',
                        'last_name' => 'adminov',
                        'email' => 'admin@gmail.com',
                        'created_at' => null,
                        'actions' => [
                            [
                                'label' => 'View',
                                'icon' => 'eye',
                                'size' => null,
                                'tooltip' => null,
                                'isDisabled' => false,
                                'isHidden' => false,
                                'route' => [
                                    'name' => 'admin.pages.admins.detail',
                                    'url' => '/admins/3'
                                ]
                            ]
                        ]
                    ]
                ],
                'pagination' => [
                    'total' => 64,
                    'perPage' => 10,
                    'currentPage' => 1,
                    'lastPage' => 13,
                    'param' => 'page'
                ]
            ],
        ];
    }
}
