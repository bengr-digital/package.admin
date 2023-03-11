<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class ImageInput extends FileInput
{
    use Concerns\CanBeMultiple;

    protected ?string $widgetName = 'input-image';

    protected ?int $widgetColumnSpan = 12;

    public function getData(Request $request): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'multiple' => $this->isMultiple(),
            'readonly' => $this->isReadonly()
        ];
    }
}
