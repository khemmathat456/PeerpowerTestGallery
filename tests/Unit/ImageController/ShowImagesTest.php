<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class ShowImagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testShowAuthTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $uploadfile =  UploadedFile::fake()->image('photo1.jpeg');
        Storage::fake('public');

        $response = $this->json('POST', '/images', [
            'fileUpload' => $uploadfile
        ]);

        $this->assertEquals($response->getData()->name, 'photo1.jpeg');
        Storage::disk('public')->assertExists($response->getData()->path);

        $user2 = factory(User::class)->create();
        $this->actingAs($user2);

        $show = $this->json('GET', '/'.$response->getData()->path);
        $show->assertStatus(500);
        Storage::disk('public')->assertExists($response->getData()->path);
    }

    public function testShowWrongVerbTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $uploadfile =  UploadedFile::fake()->image('photo1.jpeg');
        Storage::fake('public');

        $response = $this->json('POST', '/images', [
            'fileUpload' => $uploadfile
        ]);

        $this->assertEquals($response->getData()->name, 'photo1.jpeg');
        Storage::disk('public')->assertExists($response->getData()->path);

        $show = $this->json('POST', '/'.$response->getData()->path);
        $show->assertStatus(405);
    }

    public function testShowWrongIdTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $uploadfile =  UploadedFile::fake()->image('photo1.jpeg');
        Storage::fake('public');

        $response = $this->json('POST', '/images', [
            'fileUpload' => $uploadfile
        ]);

        $this->assertEquals($response->getData()->name, 'photo1.jpeg');
        Storage::disk('public')->assertExists($response->getData()->path);

        $show = $this->json('GET', '/images/Hello1234');
        $show->assertStatus(500);
    }

    public function testShowTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $uploadfile =  UploadedFile::fake()->image('photo1.jpeg');
        Storage::fake('public');

        $response = $this->json('POST', '/images', [
            'fileUpload' => $uploadfile
        ]);

        $this->assertEquals($response->getData()->name, 'photo1.jpeg');
        Storage::disk('public')->assertExists($response->getData()->path);

        $show = $this->json('GET', $response->getData()->path, ['id' => $response->getData()->name_unique]);
        $show->assertStatus(200);
    }

}
