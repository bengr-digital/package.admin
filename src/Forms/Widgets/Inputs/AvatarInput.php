<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class AvatarInput extends FileInput
{
    protected ?string $widgetName = 'input-avatar';

    protected ?int $columnSpan = 12;

    public function isMultiple(): bool
    {
        return false;
    }

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
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules()
        ];
    }
}
