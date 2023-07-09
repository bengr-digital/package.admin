<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\WidgetNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Widgets\Widget;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Closure;

class ValidWidgetId implements DataAwareRule, ValidationRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
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
