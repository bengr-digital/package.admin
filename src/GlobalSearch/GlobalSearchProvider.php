<?php

namespace Bengr\Admin\GlobalSearch;

use Bengr\Admin\Facades\Admin as BengrAdmin;

class GlobalSearchProvider
{
    public function getResults(string $query): ?GlobalSearchResults
    {
        $builder = GlobalSearchResults::make();

        if ($query) {
            foreach (BengrAdmin::getPages() as $page) {
                $page = app($page);

                if (!$page->canGloballySearch()) {
                    continue;
                }

                $results = $page->getGlobalSearchResults($query);

                if (!$results->count()) {
                    continue;
                }

                $builder->category($page->getGlobalSearchCategoryLabel(), $results);
            }
        }

        return $builder;
    }
}
