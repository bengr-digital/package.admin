<?php

namespace Bengr\Admin\Tables\Columns;

use Illuminate\Database\Eloquent\Model;

class TagsColumn extends Column
{
    protected string | \Closure | null $separator = null;

    protected int | \Closure | null $limit = null;

    public function separator(string | \Closure | null $separator = ','): static
    {
        $this->separator = $separator;

        return $this;
    }

    public function limit(int | \Closure | null $limit = 3): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate($this->separator);
    }

    public function getLimit(): ?int
    {
        return $this->evaluate($this->limit);
    }

    public function getProps(Model $record): array
    {
        return [
            'separator' => $this->getSeparator(),
            'limit' => $this->getLimit()
        ];
    }
}
