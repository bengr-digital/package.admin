<?php

namespace Bengr\Admin\Tests\Unit\Pages;

use Bengr\Admin\Tests\Support\TestResources\Models\Subpage;
use Bengr\Admin\Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    public function test_that_page_without_model_search_attributes_and_search_result_has_disabled_global_search()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GlobalSearch/DisabledOrEnabled'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GlobalSearch\\DisabledOrEnabled'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        $this->assertFalse($this->adminManager->getPageByUrl('disabled-without-global-attributes')->canGloballySearch());
        $this->assertFalse($this->adminManager->getPageByUrl('disabled-without-global-model')->canGloballySearch());
        $this->assertTrue($this->adminManager->getPageByUrl('enabled-without-global-result')->canGloballySearch());
        $this->assertTrue($this->adminManager->getPageByUrl('enabled-with-all')->canGloballySearch());
    }

    public function test_obtaining_correct_amount_of_results_based_on_default_limit()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GlobalSearch/Limits/Default'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GlobalSearch\\Limits\\Default'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        Subpage::factory()
            ->count(10)
            ->sequence(
                ['title' => 'subpage 1'],
                ['title' => 'subpage 2'],
                ['title' => 'subpage 3'],
                ['title' => 'subpage 4'],
                ['title' => 'subpage 5'],
                ['title' => 'subpage 6'],
                ['title' => 'subpage 7'],
                ['title' => 'subpage 8'],
                ['title' => 'subpage 9'],
                ['title' => 'subpage 10']
            )
            ->create();

        $results = $this->adminManager->getGlobalSearchProvider()->getResults('subpage');

        $this->assertGlobalSearchResultsCount($results, 5);
    }

    public function test_obtaining_correct_amount_of_results_based_on_custom_limit()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GlobalSearch/Limits/Custom'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GlobalSearch\\Limits\\Custom'),
            'admin.components.pages.register' => []
        ]);

        $this->adminManager->registerComponents();

        Subpage::factory()
            ->count(10)
            ->sequence(
                ['title' => 'subpage 1'],
                ['title' => 'subpage 2'],
                ['title' => 'subpage 3'],
                ['title' => 'subpage 4'],
                ['title' => 'subpage 5'],
                ['title' => 'subpage 6'],
                ['title' => 'subpage 7'],
                ['title' => 'subpage 8'],
                ['title' => 'subpage 9'],
                ['title' => 'subpage 10']
            )
            ->create();

        $results = $this->adminManager->getGlobalSearchProvider()->getResults('subpage');

        $this->assertGlobalSearchResultsCount($results, 10);
    }

    public function test_that_global_search_is_case_insensitive()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GlobalSearch/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GlobalSearch\\Plain'),
            'admin.components.pages.register' => []
        ]);

        Subpage::factory()
            ->count(2)
            ->sequence(
                ['title' => 'subpage 1'],
                ['title' => 'subpage 2'],
            )
            ->create();

        $this->adminManager->registerComponents();

        $results = $this->adminManager->getGlobalSearchProvider()->getResults('SUBPAGE');

        $this->assertGlobalSearchResultsCount($results, 2);
        $this->assertGlobalSearchResultExists(
            title: 'subpage 1',
            results: $results
        );
        $this->assertGlobalSearchResultExists(
            title: 'subpage 2',
            results: $results
        );
    }

    public function test_that_global_search_keyword_based()
    {
        config([
            'admin.components.pages.path' => $this->getTestPagePath('GlobalSearch/Plain'),
            'admin.components.pages.namespace' => $this->getTestPageNamespace('GlobalSearch\\Plain'),
            'admin.components.pages.register' => []
        ]);

        Subpage::factory()
            ->count(2)
            ->sequence(
                ['title' => 'toto je subpageos cislo'],
                ['title' => 'toto bude urcite nejaky ten subpageik'],
            )
            ->create();

        $this->adminManager->registerComponents();

        $results = $this->adminManager->getGlobalSearchProvider()->getResults('subpage');

        $this->assertGlobalSearchResultsCount($results, 2);
        $this->assertGlobalSearchResultExists(
            title: 'toto je subpageos cislo',
            results: $results
        );
        $this->assertGlobalSearchResultExists(
            title: 'toto bude urcite nejaky ten subpageik',
            results: $results
        );

        $this->adminManager->registerComponents();
    }
}
