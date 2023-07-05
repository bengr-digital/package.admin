<?php

namespace Bengr\Admin\Tests;

use Bengr\Admin\AdminServiceProvider;

/*
|--------------------------------------------------------------------------
| What I need to test in this package
|--------------------------------------------------------------------------
| - Summary and notes on what I have to test 
' - These tests musnt be relyed on configuration that uses this package
| ------------------------
| | AdminServiceProvider |
| ------------------------
| - [] test that all components that are specified in the configuration are registered correctly
| - [] test that user item of /auth/me is registered correctly
| ----------------
| | AdminManager |
| ----------------
| - [] test registering components (pages, global actions and even other custom components)
| - [] test generation navigation list
| - [] test registering items inside of usermenu
| - [] test obtaining page by url
| ---------
| | Pages |
| ---------
| -----------
| | Widgets |
| -----------
| -----------
| | Actions |
| -----------
|   - sdfdsf
| ----------
| | API |
| ----------
| - [] test that all api routes are registered correctly
| - [] test making various requests to these routes and test that they give desired response
| ----------
| | Config |
| ----------
|   - sdfsd
| ----------------
| | Others       |
| ----------------
| - maybe testing navigation, user menu, global actions, globalsearcg etc. separatly
*/

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            AdminServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
