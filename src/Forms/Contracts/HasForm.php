<?php

namespace Bengr\Admin\Forms\Contracts;

interface HasForm
{
    public function getCachedFormSchema(): array;
}
