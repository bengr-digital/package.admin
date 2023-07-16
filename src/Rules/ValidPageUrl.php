<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Pages\Page;
use Illuminate\Contracts\Validation\InvokableRule;

class ValidPageUrl implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        $page = Admin::getPageByUrl($value);

        if (!$page || !$page instanceof Page) {
            throw new PageNotFoundException;
        }
    }
}
