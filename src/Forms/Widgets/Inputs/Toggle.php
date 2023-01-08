<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class Toggle extends Input
{
    use Concerns\CanBeChecked;

    protected ?string $widgetName = 'input-toggle';

    protected int $widgetColumnSpan = 12;

    public function getType(): ?string
    {
        return 'checkbox';
    }

    public function getData(Request $request): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'checked' => $this->isChecked(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'readonly' => $this->isReadonly(),
            'rules' => $this->getRules(),
        ];
    }
}
