<?php

namespace Bengr\Admin\Forms\Widgets\Inputs;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DatePicker extends Input
{
    use Concerns\HasDate;

    protected ?string $widgetName = 'input-datepicker';

    protected ?int $widgetColumnSpan = 12;

    public function value($value = null): self
    {
        $this->value = $value ? $value->format($this->getFormat()) : null;

        return $this;
    }

    public function getType(): ?string
    {
        return 'datepicker';
    }

    public function getData(Request $request): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'format' => $this->getFormat(),
            'minDate' => $this->getMinDate(true),
            'maxDate' => $this->getMaxDate(true),
            'required' => $this->isRequired(),
            'disabled' => $this->isDisabled(),
            'hidden' => $this->isHidden(),
            'readonly' => $this->isReadonly(),
            'rules' => []
        ];
    }
}
