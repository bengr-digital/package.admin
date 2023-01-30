<?php

namespace Bengr\Admin\Tables\Columns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class IconColumn extends Column
{
    use Concerns\HasColor;
    use Concerns\HasFontSize;

    public function getValue(Model $record)
    {
        return Arr::get($record, $this->getName());
    }

    public function getProps(Model $record): array
    {
        return [
            'color' => $this->getColor(),
            'size' => $this->getSize(),
        ];
    }
}
