<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class Radio extends Input
{
    use Concerns\HasOptions;

    protected ?string $widgetName = 'input-radio';

    protected ?int $columnSpan = 12;

    public function getData(Request $request): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'required' => $this->isRequired(),
            'options' => $this->getOptions(),
            'rules' => $this->getRules()
        ];
    }
}
