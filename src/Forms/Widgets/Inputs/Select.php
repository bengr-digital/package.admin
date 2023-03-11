<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Http\Request;

class Select extends Input
{
    use Concerns\HasOptions;
    use Concerns\CanBeSearchable;
    use Concerns\CanBeMultiple;

    protected ?string $widgetName = 'input-select';

    protected ?int $widgetColumnSpan = 12;

    public function getType(): ?string
    {
        return 'select';
    }

    public function getData(Request $request): array
    {
        return [
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'required' => $this->isRequired(),
            'multiple' => $this->isMultiple(),
            'searchable' => $this->isSearchable(),
            'value' => $this->getValue(),
            'options' => $this->getOptions()
        ];
    }
}
