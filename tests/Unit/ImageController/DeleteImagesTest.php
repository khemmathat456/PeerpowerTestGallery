<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class DeleteImagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeleteAuthTest()
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

        $delete = $this->json('DELETE', '/'.$response->getData()->path);
        $delete->assertStatus(500);
        Storage::disk('public')->assertExists($response->getData()->path);
    }

    public function testDeleteWrongVerbTest()
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

        $delete = $this->json('POST', '/'.$response->getData()->path);
        $delete->assertStatus(405);
    }

    public function testDeleteNoIdTest()
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

        $delete = $this->json('DELETE', '/images/');
        $delete->assertStatus(405);
    }

    public function testDeleteTest()
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

        $delete = $this->json('DELETE', '/'.$response->getData()->path);
        $delete->assertStatus(200);
        Storage::disk('public')->assertMissing($response->getData()->path);
    }

}
