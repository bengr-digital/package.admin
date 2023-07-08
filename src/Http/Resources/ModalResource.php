<?php

namespace Bengr\Admin\Http\Resources;

use Bengr\Admin\Facades\Admin;
use Illuminate\Http\Resources\Json\JsonResource;

class ModalResource extends JsonResource
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
        $atBuilderModalsPath = $request->getPathInfo() == Admin::getApiRouteUrl('modals');

        if ($atBuilderModalsPath || !$this->getLazyload()) {
            return [
                'id' => $this->getId(),
                'type' => $this->getType(),
                'direction' => $this->getDirection(),
                'heading' => $this->getHeading(),
                'subheading' => $this->getSubheading(),
                'hasCross' => $this->hasCross(),
                'widgets' => WidgetResource::collection($this->getTransformedWidgets()),
                'actions' => ActionGroupResource::collection($this->getActions()),
            ];
        } else {
            return null;
        }
    }
}
