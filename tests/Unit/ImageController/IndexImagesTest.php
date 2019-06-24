<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class IndexImagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexAuthTest()
    {
        $response = $this->get('/images');

        $this->assertGuest($guard = null);
        $response->assertStatus(302);
    }

    public function testIndexNoImagesTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->get('/images');

        $response
            ->assertStatus(200)
            ->assertJson([]);

    }

    public function testIndexOneImagesTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $user->images()->create(factory(Images::class)
            ->raw(
                [
                    'name' => 'Lorem ipsum',
                    'size' => '100000',
                    'type' => 'image/jpeg',
                    'path' => 'image/Lorem ipsum',
                ]
            ));

        $response = $this->get('/images');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                'name' => 'Lorem ipsum',
                'size' => '100000',
                'type' => 'image/jpeg',
                'path' => 'image/Lorem ipsum',
                ]
            ]);
    }

    public function testIndexTwoImagesTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $user->images()->create(factory(Images::class)
            ->raw(
                [
                    'name' => 'Lorem ipsum',
                    'size' => '100000',
                    'type' => 'image/jpeg',
                    'path' => 'image/Lorem ipsum',
                ]
            ));
        $user->images()->create(factory(Images::class)
            ->raw(
                [
                    'name' => 'Lorem ipsum2',
                    'size' => '200000',
                    'type' => 'image/png',
                    'path' => 'image/Lorem ipsum2',
                ]
            ));

        $response = $this->get('/images');

        $response
            ->assertStatus(200)
            ->assertJson([
                [
                    'name' => 'Lorem ipsum',
                    'size' => '100000',
                    'type' => 'image/jpeg',
                    'path' => 'image/Lorem ipsum',
                ],
                [
                    'name' => 'Lorem ipsum2',
                    'size' => '200000',
                    'type' => 'image/png',
                    'path' => 'image/Lorem ipsum2',
                ]
            ]);
    }

}
