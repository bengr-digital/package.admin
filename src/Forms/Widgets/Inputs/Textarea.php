<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class Textarea extends Input
{
    protected ?string $widgetName = 'input-textarea';

    protected int $widgetColumnSpan = 12;

    public function getData(Request $request): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'placeholder' => $this->getPlaceholder(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules(),
        ];
    }
}
