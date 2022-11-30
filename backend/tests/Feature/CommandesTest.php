<?php

namespace Tests\Feature;

use Tests\TestCase;

class CommandesTest extends TestCase
{
    public function test_ClearLogs()
    {
        $this->artisan('logs:clear')->assertSuccessful();
    }

    public function test_ExpiredNotifications()
    {
        $this->artisan('notification:expired')->assertSuccessful();
    }

    public function test_Backup()
    {
        $this->artisan('backup:run')->assertSuccessful();
    }
}
