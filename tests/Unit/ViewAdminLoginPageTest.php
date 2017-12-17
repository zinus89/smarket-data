<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ViewAdminLoginPageTest extends TestCase
{
    use DatabaseMigrations;
    public function testAdminLoginPage()
    {
        $admin = factory(User::class)->create(['name' => 'Administrator', 'user_type' => 'administator']);

        $this->browse(function ($browser) {
        $browser->visit('/home')
            -> assertSee('Welcome Administator');
    });
    }
}
