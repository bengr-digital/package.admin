<?php

namespace Bengr\Admin\Tables\Columns;

use Illuminate\Database\Eloquent\Model;

class BadgeColumn extends Column
{
    use Concerns\HasColors;

    public function getProps(Model $record): array
    {
        return [
            'color' => $this->getColor($record)
        ];
    }
}
