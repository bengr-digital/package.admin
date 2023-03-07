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
            'icon' => $this->hasIcon() ? [
                'name' => $this->getIconName(),
                'activeName' => $this->getIconName(),
                'type' => $this->getIconType(),
            ] : null,
            'color' => $this->getColor(),
            'size' => $this->getSize(),
            'tooltip' => $this->getTooltip(),
            'isDisabled' => $this->isDisabled(),
            'isHidden' => $this->isHidden(),
            'confirm' => $this->hasConfirm() ? [
                'title' => $this->getConfirmTitle(),
                'description' => $this->getConfirmDescription(),
                'color' => $this->getConfirmColor(),
                'confirmText' => $this->getConfirmConfirmText(),
                'cancelText' => $this->getConfirmCancelText(),
            ] : null,
            'redirect' => $this->getRedirectUrl([
                'record' => $this->getRecord()
            ]) ? [
                'name' => $this->getRedirectName([
                    'record' => $this->getRecord()
                ]),
                'url' => $this->getRedirectUrl([
                    'record' => $this->getRecord()
                ]),
                'inNewTab' => $this->openInNewTab()
            ] : null,
            'modal' => $this->getModalId() && $this->getModalEvent() ? [
                'id' => $this->getModalId(),
                'event' => $this->getModalEvent()
            ] : null,
            'call' => $this->hasHandle() ? [
                'name' => $this->getName(),
                'widget_id' => $this->getHandleWidgetId(),
                'download' => $this->isDownload()
            ] : null

        ];
    }
}
