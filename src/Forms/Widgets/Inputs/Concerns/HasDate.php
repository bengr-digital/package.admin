<?php

namespace Bengr\Admin\Forms\Widgets\Inputs\Concerns;

use Carbon\Carbon;

trait HasDate
{
    protected ?string $format = "d. m. Y";

    protected ?Carbon $minDate = null;

    protected ?Carbon $maxDate = null;

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function minDate(Carbon $minDate): self
    {
        $this->minDate = $minDate;

        return $this;
    }

    public function maxDate(Carbon $maxDate): self
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getMinDate(?bool $formated = false): Carbon | string | null
    {
        if ($formated && $this->minDate) {
            return Carbon::make($this->minDate)->format($this->getFormat());
        }

        return $this->minDate;
    }

    public function getMaxDate(?bool $formated = false): Carbon | string | null
    {
        if ($formated && $this->maxDate) {
            return Carbon::make($this->maxDate)->format($this->getFormat());
        }

        return $this->maxDate;
    }
}
