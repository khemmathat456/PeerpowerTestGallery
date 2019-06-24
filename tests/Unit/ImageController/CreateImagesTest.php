<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class CreateImagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexAuthTest()
    {
        $response = $this->get('/images/create');

        $this->assertGuest($guard = null);
        $response->assertStatus(302);
    }

    public function testIndexNoImagesTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('/images/create');

        $response
            ->assertStatus(200);
    }

}
