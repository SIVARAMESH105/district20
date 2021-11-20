<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\NotificationHelper;
use Log;

/**
 * NOTE : ./vendor/bin/phpunit --filter FireBaseTest
 *
 */
class FireBaseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {        
        NotificationHelper::sendEventCreatedPushNotification(43);
        $this->assertTrue(true);
    }
}
