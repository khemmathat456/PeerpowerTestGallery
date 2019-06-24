<?php

namespace Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Images;

class StoreImagesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStoreAuthTest()
    {
        $response = $this->json('POST', '/images');

        $this->assertGuest($guard = null);
        $response->assertStatus(401);
    }

    public function testStoreRequestValidationNoDataTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $response = $this->json('POST', '/images');

        $response
            ->assertStatus(422)
            //  Error msg = 'File is required'
            ->assertJsonValidationErrors(['fileUpload']['0']);
    }

    public function testStoreUploadWrongTypeTest()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $uploadfile = UploadedFile::fake()->create('wrongType');
        Storage::fake('public');

        $response = $this->json('POST', '/images', [
            'fileUpload' => $uploadfile
        ]);

        $response
            ->assertStatus(422)
            //  Error msg = 'File type not supported'
            ->assertJsonValidationErrors(['fileUpload']['0']);
    }

//    public function testStoreUploadSizeLimitTest()
//    {
//        $user = factory(User::class)->create();
//        $this->actingAs($user);
//        $uploadfile = UploadedFile::fake()->image('wrongType.jpeg', 33000, 33000);
//        Storage::fake('public');
//
//        $response = $this->json('POST', '/images', [
//            'fileUpload' => $uploadfile
//        ]);

//        $response
//            ->assertStatus(422)
//              Error msg = 'File Maximum is 10MB'
//            ->assertJsonValidationErrors(['fileUpload']['0']);
//    }

    public function testStoreUploadFileTest()
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
    }

}
