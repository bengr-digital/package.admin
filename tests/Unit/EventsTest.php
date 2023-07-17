<?php

namespace Bengr\Admin\Tests\Unit;

use Bengr\Admin\Events\ServingAdmin;
use Bengr\Admin\Tests\TestCase;

class EventsTest extends TestCase
{
    public function test_callback_is_correctly_registered_into_serving_event()
    {
        $testingStringVariable = '';

        $this->adminManager->onServing(function () use (&$testingStringVariable) {
            $testingStringVariable = 'serving callback';
        });

        ServingAdmin::dispatch();

        $this->assertEquals('serving callback', $testingStringVariable);
    }

    public function test_serving_event_is_correctly_dispatched_after_making_api_request()
    {
        $testingStringVariable = '';

        $this->adminManager->onServing(function () use (&$testingStringVariable) {
            $testingStringVariable = 'serving callback';
        });

        $this->get('/admin/builder/pages');

        $this->assertEquals('serving callback', $testingStringVariable);
    }
}
