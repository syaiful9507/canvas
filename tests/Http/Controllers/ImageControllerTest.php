<?php

namespace Canvas\Tests\Http\Controllers;

use Canvas\Models\User;
use Canvas\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class UploadsControllerTest.
 *
 * @covers \Canvas\Http\Controllers\UploadsController
 */
class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testEmptyUploadIsValidated(): void
    {
        $this->markTestSkipped();

        Storage::fake(config('canvas.storage_disk'));

        $this->actingAs(User::factory()->create(), 'canvas')
             ->putJson(route('canvas.uploads.store'), [null])
             ->assertBadRequest();
    }

    public function testUploadedImageCanBeStored(): void
    {
        $this->markTestSkipped();

        Storage::fake(config('canvas.storage_disk'));

        $response = $this->actingAs(User::factory()->create(), 'canvas')
                         ->putJson(route('canvas.uploads.store'), [$file = UploadedFile::fake()->image('1.jpg')])
                         ->assertSuccessful();

        $path = sprintf('%s/%s/%s', config('canvas.storage_path'), 'images', $file->hashName());

        $this->assertSame(
            $response->getOriginalContent(),
            Storage::disk(config('canvas.storage_disk'))->url($path)
        );

        $this->assertIsString($response->getContent());

        Storage::disk(config('canvas.storage_disk'))->assertExists($path);
    }

    public function testDeleteUploadedImage(): void
    {
        $this->markTestSkipped();

        Storage::fake(config('canvas.storage_disk'));

        $this->actingAs(User::factory()->create(), 'canvas')
             ->delete(route('canvas.uploads.destroy', null))->assertBadRequest();

        $this->actingAs(User::factory()->create(), 'canvas')
             ->delete(route('canvas.uploads.destroy', [$file = UploadedFile::fake()->image('1.jpg')]))
             ->assertSuccessful();

        $path = sprintf('%s/%s/%s', config('canvas.storage_path'), 'images', $file->hashName());

        Storage::disk(config('canvas.storage_disk'))->assertMissing($path);
    }
}
