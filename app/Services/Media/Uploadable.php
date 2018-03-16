<?php

namespace App\Services\Media;

use App\Models\Media;
use Illuminate\Support\Carbon;

trait Uploadable
{

    public $imageGenerator;

    /**
     * @var array
     */
    protected $data = [
        'path'           => null,
        'slug'           => null,
        'directory_path' => null,
        'extension'      => null,
        'mime_type'      => null,
        'file_type'      => null,
        'variations'     => [],
        'properties'     => [
            'alt_text'    => null,
            'caption'     => null,
            'description' => null,
        ],
        'storage_disk'   => null,
        'uploaded_by'    => null,
    ];

    /**
     * @var
     */
    protected $file;

    /**
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    public function getDisk()
    {
        return \Storage::disk(config('aps.media.disk'));
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return "media/" . date('Y') . "/" . date("m");
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getValidSlug()
    {
        $this->data['slug'] = $this->sluggify($this->getFileName());

        return $this->data['slug'];
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        $extension = "." . $this->file->getClientOriginalExtension();

        return str_replace($extension, "", $this->file->getClientOriginalName());

        //$name = str_slug($name, '-') . '-' . Carbon::now() . '-' . str_random(4) . '-' . $width . 'x' . $height;

        // return md5($name);
    }

    /**
     * @return $this
     */
    public function setData()
    {
        $this->data['directory_path'] = $this->getDirectory();

        $this->data['extension'] = $this->file->getClientOriginalExtension();

        $this->data['mime_type'] = $this->file->getClientMimeType();

        $this->data['storage_disk'] = config('aps.media.disk');

        $this->data['uploaded_by'] = request()->user()->id;

        return $this;

    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->data['file_type'] = $type;

        return $this;
    }

    /**
     * @param        $name
     * @param string $columnName
     * @return string
     * @throws \Exception
     */
    protected function sluggify($name, $columnName = 'slug')
    {
        $slug = str_slug($name);

        $fileSlugs = Media::where($columnName, 'like', $slug . '%')->get();

        // If we haven't used it before then we are all good.
        if (
            !$fileSlugs->contains($columnName, $slug) &&
            !$this->mediaExistInStorage($slug)
        ) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= $fileSlugs->count() + 10; $i++) {

            $newFileSlug = $slug . '-' . $i;

            if (!$fileSlugs->contains($columnName, $newFileSlug)) return $newFileSlug;

        }

        throw new \Exception('Can not create a unique file Slug');
    }

    protected function mediaExistInStorage($slug)
    {
        $path = $this->getDirectory() . '/' . $slug . '.' . $this->file->getClientOriginalExtension();

        return $this->getDisk()->exists($path);
    }

}