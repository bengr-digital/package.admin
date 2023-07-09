<?php

namespace Bengr\Admin\Tests\Support\TestResources\Pages\ForNavigation\ModifiedProperties;

use Bengr\Admin\Pages\Page;

class ModifiedProperties extends Page
{
    protected ?string $navigationLabel = 'Testing navigation label';

    protected ?string $navigationIconName = 'testing';

    protected ?string $navigationIconType = 'filled';

    protected ?string $navigationActiveIconName = 'active_testing';

    protected ?string $navigationActiveIconType = 'outlined';

    protected ?string $slug = 'testing/slug';

    protected function getNavigationBadge(): ?string
    {
        return 12;
    }

    protected function getNavigationBadgeColor(): ?string
    {
        return 'red';
    }
}
