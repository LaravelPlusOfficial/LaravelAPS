<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddAvatarTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function only_confirmed_users_can_add_avatars()
    {
        $user = $this->create(User::class);

        $this->withoutExceptionHandling()
            ->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('admin.avatar.update', $user->id))->assertStatus(302);
        $this->deleteJson(route('admin.avatar.destroy', $user->id))->assertStatus(302);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $user = $this->signInConfirmed();

        $this->post(route('admin.avatar.update', $user->id), [
            'avatar' => 'not-valid'
        ])
            ->assertStatus(302)
            ->assertSessionHasErrors('avatar');
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        list($disk, $user, $path) = $this->uploadProfileImage();

        $this->assertEquals($path, $user->fresh()->avatar);

        $disk->assertExists($path);

        $disk->deleteDirectory('avatars');
    }

    /** @test */
    public function avatar_should_be_of_required_dimensions()
    {
        list($disk, $user, $path) = $this->uploadProfileImage();

        $imageData = getimagesize($disk->path($path));

        $this->assertEquals((int)config('aps.media.avatar.width', 300), $imageData[0]);

        $this->assertEquals(config('aps.media.avatar.height', 300), $imageData[1]);

        $disk->deleteDirectory('avatars');
    }

    /** @test */
    public function uploaded_profile_picture_should_be_updateable()
    {
        $this->withoutExceptionHandling();

        list($disk, $user, $path) = $this->uploadProfileImage();

        $this->assertEquals($path, $user->fresh()->avatar);

        $disk->assertExists($path);

        Carbon::setTestNow(Carbon::tomorrow());

        list($disk, $user, $newPath) = $this->uploadProfileImage($user, $disk, Carbon::getTestNow()->timestamp);

        $disk->assertMissing($path);

        $disk->deleteDirectory('avatars');
    }

    /** @test */
    public function user_can_remove_avatar()
    {
        $this->withoutExceptionHandling();

        $user = $this->signInConfirmed();

        $this->deleteJson(route('admin.avatar.destroy', $user->id))->assertStatus(200);
    }

    /**
     * @param null $user
     * @param null $disk
     * @return array
     */
    protected function uploadProfileImage($user = null, $disk = null, $time = null): array
    {
        if (!$disk) {

            Storage::fake('public/avatars');

            $disk = Storage::disk('public');
        }

        $this->withoutExceptionHandling();

        $user = $this->signInConfirmed($user);

        $time = $time ?? time();

        $name = md5("{$user->id}{$user->email}{$time}") . '.jpg';

        $path = sprintf('avatars/%s', $name);

        $file = UploadedFile::fake()->image($name, 500, 400);

        $this->post(route('admin.avatar.update', $user->id), ['avatar' => $file]);

        return array($disk, $user, '/' . $path);
    }


}
