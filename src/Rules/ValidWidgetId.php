<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ValidWidgetId implements DataAwareRule, InvokableRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function __invoke($attribute, $value, $fail)
    {
        $page = Admin::getPageByUrl($this->data['url']);

        try {
            $widget = $page->getWidget($value);

            if (!$widget || !$widget instanceof Widget) {
                throw new WidgetNotFoundException;
            }
        } catch (\Throwable $e) {
            throw new WidgetNotFoundException;
        }
    }
}
