<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
//        $user = factory(User::class)->make();
//        dd($user->name);
//        $this->assertDatabaseHas('users', ['name' => $user->name]);
        $this->assertTrue(true);
    }
}
