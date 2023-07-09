<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\CustomSlug\Plain;

use Bengr\Admin\Pages\Page;

class PlainWithPrefixAndSufix extends Page
{
    protected ?string $slug = '/plain/with-prefix/and-sufix/';
}
