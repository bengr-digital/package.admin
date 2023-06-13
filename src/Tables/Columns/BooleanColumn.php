<?php

namespace Bengr\Admin\Tables\Columns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class BooleanColumn extends Column
{
    public function getValue(Model $record)
    {
        return Arr::get($record, $this->getName()) ? true : false;
    }
}
