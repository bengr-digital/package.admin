<?php

namespace Bengr\Admin\Forms\Concerns;

use Bengr\Admin\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait InteractsWithForm
{
    use HasSchema;

    protected Form $form;

    public function getForm(Collection $params): Form
    {
        if (!isset($this->form)) {
            $this->form = Form::make($this, $params);
        }

        return $this->form;
    }
}
