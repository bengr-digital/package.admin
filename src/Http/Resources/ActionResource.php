<?php

namespace Bengr\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
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
            'label' => $this->getLabel(),
            'icon' => [
                'name' => $this->getIconName(),
                'activeName' => $this->getIconName(),
                'type' => $this->getIconType(),
            ],
            'color' => $this->getColor(),
            'size' => $this->getSize(),
            'tooltip' => $this->getTooltip(),
            'isDisabled' => $this->isDisabled(),
            'isHidden' => $this->isHidden(),
            'confirm' => $this->hasConfirm() ? [
                'title' => $this->getConfirmTitle(),
                'description' => $this->getConfirmDescription(),
            ] : null,
            'redirect' => $this->getRouteName() && $this->getRouteUrl() ? [
                'name' => $this->getRouteName(),
                'url' => $this->getRouteUrl(),
            ] : null,
            'modal' => $this->getModalId() && $this->getModalEvent() ? [
                'id' => $this->getModalId(),
                'event' => $this->getModalEvent()
            ] : null,
            'call' => $this->hasHandle() ? [
                'name' => $this->getName(),
                'widget_id' => $this->getHandleWidgetId()
            ] : null

        ];
    }
}
