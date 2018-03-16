<?php

namespace App\Services\Media;


use App\Services\Media\Tools\OptimizeImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Filesystem\FilesystemAdapter;
use Intervention\Image\Facades\Image;

class ImageGenerator
{

    /**
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var FilesystemAdapter
     */
    protected $disk;

    protected $image;

    /**
     * ImageGenerator constructor.
     * @param UploadedFile $file
     * @param FilesystemAdapter $disk
     */
    public function __construct(UploadedFile $file, FilesystemAdapter $disk)
    {
        $this->file = $file;

        $this->disk = $disk;
    }

    public function fit($width, $height = null)
    {
        return $height ? $this->image->fit($width, $height) : $this->image->fit($width);
    }

    /**
     * @param $image
     * @param $saveAs
     * @param string $type
     * @return array
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function save($image, $saveAs, $type = '')
    {
        $stream = $image->stream($this->file->getClientOriginalExtension());

        $this->disk->put($saveAs, $stream);

        $this->disk->path($saveAs);

        $this->optimize($this->disk->path($saveAs));

        return [
            'path'   => '/' . $saveAs,
            'size'   => $this->disk->size($saveAs),
            'width'  => $image->width(),
            'height' => $image->height(),
            'mime'   => $this->disk->getMimetype($saveAs),
            'name'   => $type,
            'type'   => 'image'
        ];
    }

    /**
     * Set intervention instance to variable for use
     *
     * @return ImageGenerator
     */
    public function setInterventionImage()
    {
        $this->image = Image::make($this->file);;

        $orientation = $this->image->exif('Orientation');

        if (!empty($orientation)) {

            switch ($orientation) {
                case 8:
                    $this->image = $this->image->rotate(90);
                    break;

                case 3:
                    $this->image = $this->image->rotate(180);
                    break;

                case 6:
                    $this->image = $this->image->rotate(-90);
                    break;
            }

        }

        return $this;
    }


    /**
     * @param UploadedFile $file
     *
     * @return ImageGenerator
     */
    public function setFile(UploadedFile $file): ImageGenerator
    {
        $this->file = $file;

        return $this;
    }

    protected function optimize($imagePath)
    {
        (new OptimizeImage($imagePath))->optimize();
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

}