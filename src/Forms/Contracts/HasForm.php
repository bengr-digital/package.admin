<?php

namespace Bengr\Admin\Forms\Contracts;

use Bengr\Admin\Forms\Widgets\Inputs\Input;
use Illuminate\Database\Eloquent\Model;

interface HasForm
{
    public function getCachedFormSchema(): array;
    public function getCachedFormInputs(): array;
    public function getCachedFormInput(string $name): ?Input;
    public function getCachedValue(?string $name = null);
    public function getRecord(): ?Model;
    public function getModel(): ?string;
}
