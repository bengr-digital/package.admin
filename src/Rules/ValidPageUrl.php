<?php

namespace Bengr\Admin\Rules;

use Bengr\Admin\Exceptions\PageNotFoundException;
use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Pages\Page;
use Illuminate\Contracts\Validation\ValidationRule;

use Closure;

class ValidPageUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $page = Admin::getPageByUrl($value);


        if (!$page || !$page instanceof Page) {
            throw new PageNotFoundException;
        }
    }
}
