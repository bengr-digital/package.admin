<?php

namespace Bengr\Admin\Forms;

use Bengr\Admin\Forms\Contracts\HasForm;
use Illuminate\Support\Collection as SupportCollection;

class Form
{
    protected HasForm $formResource;

    protected SupportCollection $params;

    final public function __construct(HasForm $formResource, SupportCollection $params)
    {
        $this->formResource = $formResource;
        $this->params = $params;
    }

    public static function make(HasForm $formResource, SupportCollection $params): static
    {
        return app(static::class, ['formResource' => $formResource, 'params' => $params]);
    }

    public function getSchema(): array
    {
        return $this->formResource->getCachedFormSchema();
    }
}
