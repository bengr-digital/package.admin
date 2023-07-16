<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\ModalNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Modals\Modal;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class ValidModalId implements DataAwareRule, InvokableRule
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
            $modal = $page->getModal($value);

            if (!$modal || !$modal instanceof Modal) {
                throw new ModalNotFoundException;
            }
        } catch (\Throwable $e) {
            throw new ModalNotFoundException;
        }
    }
}
