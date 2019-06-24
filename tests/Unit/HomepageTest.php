<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class HomepageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthRedirectTest()
    {
        $response = $this->get('/');
        $this->assertGuest($guard = null);

        // Don't auth get redirect
        $response->assertStatus(302);
    }

    public function testAuthPassTest()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    public function testOverviewHaveNotImagesTest()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/');

        $this->assertAuthenticatedAs($user, $guard = null);
        $response->assertViewHas('size_by_type', null);
        $response->assertViewHas('total_images', null);
        $response->assertViewHas('counter', null);
        $response->assertViewHas('data_used', null);

    }

    public function testOverviewHaveOneImagesTest()
    {
        // Arrange
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(Images::class)
            ->create(
                [
                    'user_id'=>$user->id,
                    'size' => '100000',
                    'type' => 'image/jpeg',
                ]
            );

        // Act
        $response = $this->get('/');

        // Assert
        $this->assertAuthenticatedAs($user, $guard = null);
        $response->assertViewHas('size_by_type', ['image/jpeg' => 100000]);
        $response->assertViewHas('total_images', '1');
        $response->assertViewHas('counter', ['image/jpeg' => 1]);
        $response->assertViewHas('data_used', '100000');
    }

    public function testOverviewHaveTwoImagesTest()
    {
        $user = factory(User::class)->create();

        $user->images()->create(factory(Images::class)
            ->raw(
                [
                    'size' => '100000',
                    'type' => 'image/jpeg',
                ]
            ));

        $user->images()->create(factory(Images::class)
            ->raw(
                [
                    'size' => '2000000',
                    'type' => 'image/png',
                ]
            ));

        $response = $this->actingAs($user)->get('/');

        $this->assertAuthenticatedAs($user, $guard = null);
        $response->assertViewHas('size_by_type', ['image/jpeg' => 100000, 'image/png' => 2000000]);
        $response->assertViewHas('total_images', '2');
        $response->assertViewHas('counter', ['image/jpeg' => 1, 'image/png' => 1]);
        $response->assertViewHas('data_used', '2100000');
    }

}
