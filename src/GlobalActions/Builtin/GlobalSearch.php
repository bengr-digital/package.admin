<?php

namespace Bengr\Admin\GlobalActions\Builtin;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\GlobalActions\GlobalAction;
use Bengr\Admin\Http\Resources\GlobalSearchResource;

class GlobalSearch extends GlobalAction
{
    protected ?string $name = 'global-search';

    protected array $middlewares = ['auth:admin'];

    public function call(array $payload = [])
    {
        $results = BengrAdmin::getGlobalSearchProvider()->getResults($payload['query'] ?? '');

        return GlobalSearchResource::collection($results->getCategories());
    }
}
