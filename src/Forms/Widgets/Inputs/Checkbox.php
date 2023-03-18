<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Checkbox extends Input
{
    use Concerns\CanBeChecked;

    protected ?string $widgetName = 'input-checkbox';

    protected ?int $widgetColumnSpan = 12;

    public function getType(): ?string
    {
        return 'checkbox';
    }

    public function getValueFromData(array | Model | null $data)
    {
        if (!$data || !Arr::get($data, $this->getName())) {
            $this->checked($this->getDefaultValue() ?? false);

            return $this->getDefaultValue() ?? false;
        };

        $this->checked(true);

        return true;
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
            'rules' => $this->getRules()
        ];
    }
}
