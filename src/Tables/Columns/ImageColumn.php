<?php

namespace Bengr\Admin\Tables\Columns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ImageColumn extends Column
{
    protected int | string | \Closure | null $imageWidth = 64;

    protected int | string | \Closure | null $imageHeight = 64;

    protected bool | \Closure $isCircular = false;

    public function imageWidth(int | string | \Closure | null $imageWidth): static
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    public function imageHeight(int | string | \Closure | null $imageHeight): static
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    public function circular(bool | \Closure $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    public function getImageWidth(): ?int
    {
        return $this->evaluate($this->imageWidth);
    }

    public function getImageHeight(): ?int
    {
        return $this->evaluate($this->imageHeight);
    }

    public function isCircular(): bool
    {
        return $this->evaluate($this->isCircular);
    }

    public function getValue(Model $record)
    {
        $record = Str::of('avatar')->contains('.') ? Arr::get($record, Str::of('causer.avatar')->beforeLast('.')->toString()) : $record;

        if (array_key_exists('Spatie\MediaLibrary\HasMedia', class_implements($record)) && array_key_exists('Spatie\MediaLibrary\InteractsWithMedia', class_uses($record))) {
            return $record->getFirstMediaUrl(Str::of('avatar')->contains('.') ? Str::of($this->getName())->afterLast('avatar')->toString() : $this->getName());
        }

        return null;
    }

    public function getProps(Model $record): array
    {
        return [
            'width' => $this->getImageWidth(),
            'height' => $this->getImageHeight(),
            'isCircular' => $this->isCircular(),
        ];
    }
}
