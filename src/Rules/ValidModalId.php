<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\ModalNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Modals\Modal;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Closure;

class ValidModalId implements DataAwareRule, ValidationRule
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
            $modal = $page->getModal($value);

            if (!$modal || !$modal instanceof Modal) {
                throw new ModalNotFoundException;
            }
        } catch (\Throwable $e) {
            throw new ModalNotFoundException;
        }
    }
}
