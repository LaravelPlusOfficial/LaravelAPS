<?php

namespace App\Services\Avatar;


use App\Models\User;
use App\Services\Avatar\Contract\AvatarContract;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;

class Avatar implements AvatarContract
{

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $disk;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $file;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $width;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $height;

    protected $name;

    /**
     * @var
     */
    protected $extension;

    /**
     * Avatar constructor.
     */
    public function __construct()
    {
        $this->width = config('aps.media.site_default_user_avatar.width', 300);

        $this->height = config('aps.media.site_default_user_avatar.height', 300);

        $this->disk = \Storage::disk(config('aps.media.disk', 'public'));
    }

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function store(User $user, UploadedFile $file)
    {
        $this->user = $user;

        $this->file = $file;

        return $this->storeToUser();
    }

    /**
     * @param User $user
     * @param string $url
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function storeFromUrl(User $user, string $url)
    {
        $this->user = $user;

        $stream = $this->getStream($url);

        return $this->storeToUser($stream);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user)
    {
        $this->user = $user;

        $this->deleteIfAvatarExisted();

        //$this->setDefaultAvatar();

        return response()->json(['avatar' => $this->user->fresh()->avatar], 200);
    }

    /**
     * @param null $stream
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function storeToUser($stream = null)
    {
        $stream = $stream ?: $this->getStream();

        $saveAs = $this->getSavePath();

        $this->deleteIfAvatarExisted();

        if ($this->disk->put($saveAs, $stream)) {

            $this->user->update([
                'avatar' => '/' . $saveAs
            ]);

            return response()->json(['avatar' => $this->user->fresh()->avatar], 200);
        }

        return response("Error", 500);
    }

    /**
     * @return $this
     */
    protected function deleteIfAvatarExisted()
    {
        if ($this->user->avatar) {
            if ($this->disk->exists($this->user->avatar)) {
                $this->disk->delete($this->user->avatar);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getSavePath()
    {
        $time = Carbon::now()->timestamp;

        $name = $this->name ?: md5("{$this->user->id}{$this->user->email}{$time}");

        return "avatars/{$name}." . $this->extension;
    }

    /**
     * @param null $url
     * @return mixed
     */
    protected function getStream($url = null)
    {
        $image = Image::make($url ?: $this->file)->encode('jpg')->fit($this->width, $this->height);

        $this->extension = $this->file ? $this->file->getClientOriginalExtension() : $image->extension;

        $stream = $image->stream($this->extension ?: null);

        return $stream;
    }

}