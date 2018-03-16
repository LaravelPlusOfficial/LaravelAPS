<?php
namespace App\Services\Media;


use Illuminate\Http\UploadedFile;

interface Uploader
{

    /**
     * Upload media
     *
     * @param UploadedFile $file
     * @return mixed
     */
    public function upload(UploadedFile $file);

    public function getDisk();

    public function getDirectory();

    public function getValidSlug();

    public function getFileName();

    public function setData();

    public function setType($type);

    public function setImageGenerator(UploadedFile $file);
}