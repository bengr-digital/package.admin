<?php

namespace Bengr\Admin\Tables\Columns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class TextColumn extends Column
{
    use Concerns\HasColor;
    use Concerns\HasDescription;
    use Concerns\HasFontSize;
    use Concerns\HasFontWeight;
    use Concerns\HasFormat;


    public function getValue(Model $record)
    {
        if ($this->hasFormat()) {
            return (new Carbon(Arr::get($record, $this->getName())))->format($this->getFormat());
        }

        return Arr::get($record, $this->getName());
    }

    public function getProps(Model $record): array
    {
        return [
            'color' => $this->getColor(),
            'size' => $this->getSize(),
            'weight' => $this->getWeight(),
            'description' => [
                'above' => $this->getDescriptionAbove($record),
                'below' => $this->getDescriptionBelow($record),
            ]
        ];
    }
}
