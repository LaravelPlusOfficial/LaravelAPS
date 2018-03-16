<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MediaUploadTest extends TestCase
{

    use RefreshDatabase;

    public function tearDown()
    {
        Storage::disk('public')->deleteDirectory('media');
    }

    /** @test */
    public function image_can_be_uploaded()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public/media');

        $response = $this->uploadImage('image file.jpg');

        $path = sprintf('media/%s/%s/image-file.jpg', date('Y'), date('m'));

        Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function two_images_cannot_be_stored_of_same_name()
    {
        $this->withoutExceptionHandling();

        Storage::fake('public/media');

        $responseOne = $this->uploadImage('image file.jpg');

        $path = sprintf('media/%s/%s/image-file.jpg', date('Y'), date('m'));

        Storage::disk('public')->assertExists($path);

        $responseTwo = $this->uploadImage('image file.jpg');

        $this->assertNotEquals($responseOne['path'], $responseTwo['path']);
    }

    /** @test */
    public function uploaded_image_generate_required_variations()
    {
        Storage::fake('public/media');

        $this->uploadImage('image file.jpg');

        $imageVariations = config('aps.media.image_variations');

        foreach ($imageVariations as $type => $variation) {

            $suffix = $type == 'original' ? null : "-{$variation['width']}x{$variation['height']}";

            // public/media/year/month/name-suffix.extension
            // public/media/2018/2/name.jpg -> keep original
            // public/media/2018/2/name-1200x600.jpg keep variations
            $path = sprintf('media/%s/%s/image-file%s.jpg', date('Y'), date('m'), $suffix);

            Storage::disk('public')->assertExists($path);
        }

    }

    /** @test */
    public function image_can_be_assigned_as_post_featured_image()
    {
        $image = $this->uploadImage();

        Storage::fake('public/media');

        $data = $this->make(Post::class, [
            'featured_image_id' => $image['id']
        ])->toArray();

        $response = $this->post(route('admin.post.store'), $data);

        $post = Post::whereId(json_decode($response->getContent(), 1)['id'])->with('featuredImage')->firstOrFail();

        $this->assertEquals($image['id'], $post->featuredImage->id);
    }

    /** @test */
    public function on_media_deletion_image_variations_should_be_removed_from_storage_disk()
    {
        $this->withoutExceptionHandling();

        $image = $this->uploadImage('name.jpg');

        foreach ($image['variations'] as $variation) {
            $this->assertTrue(Storage::disk('public')->exists($variation['path']));
        }

        $this->delete(route('admin.media.destroy', $image['id']))
            ->assertStatus(200);

        foreach ($image['variations'] as $type => $variation) {
            $this->assertNotTrue(Storage::disk('public')->exists($variation['path']), "{$type} variation not deleted");
        }
    }

    /**
     * @param string $fileName
     * @return array
     */
    protected function uploadImage($fileName = 'image file.jpg')
    {
        Carbon::setTestNow(Carbon::now());

        Storage::fake('public/media');

        $this->signInConfirmed();

        $file = UploadedFile::fake()->image($fileName);

        $response = $this->post(route('admin.media.store'), ['file' => $file]);

        return json_decode($response->getContent(), 1);

    }


}
