<?php

namespace App\Services\Media;

use App\Models\Media;
use Illuminate\Http\UploadedFile;

class ImageUploader implements Uploader
{
    use Uploadable;

    /**
     * @var
     */
    protected $interventionImage;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $variations;

    protected $validSlug;


    /**
     * ImageUploader constructor.
     */
    public function __construct()
    {
        $this->variations = config('aps.media.image_variations');
    }

    /**
     * @param UploadedFile $file
     *
     * @return Media
     * @throws \Exception
     */
    public function upload(UploadedFile $file)
    {
        $this->file = $file;

        $file = $this->setImageGenerator($file)
            ->saveOriginal()
            ->saveVariations()
            ->setType('image')
            ->setData()
            ->createMedia();

        // Required for Medium Editor
        // See -> https://github.com/orthes/medium-editor-insert-plugin/wiki/v2.x-Server-response
        $fileUrl = [
            'files' => [
                [
                    'url' => $file->path
                ]
            ]
        ];

        return response()->json(
            $file->toArray() + $fileUrl
        );
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function saveOriginal()
    {
        $this->validSlug = $this->getValidSlug();

        $saveAs = $this->getDirectory() . "/" . $this->validSlug . '.' . $this->file->getClientOriginalExtension();

        $originalData = $this->imageGenerator->save($this->imageGenerator->getImage(), $saveAs, 'original');

        $this->data['variations']['original'] = $originalData;

        $this->data['path'] = "/" . $saveAs;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function saveVariations()
    {
        foreach ($this->variations as $variationType => $variation) {

            $saveAs = $this->getVariationSaveAs($variation);

            $image = $this->imageGenerator->fit($variation['width'], $variation['height']);

            $variationTypeData = $this->imageGenerator->save($image, $saveAs, $variationType);

            $this->data['variations'][$variationType] = $variationTypeData;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    protected function createMedia()
    {
        return (new CreateMedia($this->data))->create();
    }


    /**
     * @param UploadedFile $file
     * @return $this
     */
    public function setImageGenerator(UploadedFile $file)
    {
        $this->imageGenerator = (new ImageGenerator($file, $this->getDisk()))->setInterventionImage();

        return $this;
    }

    /**
     * @param $variation
     * @return string
     * @throws \Exception
     */
    protected function getVariationSaveAs($variation): string
    {
        $saveAs = $this->getDirectory() . "/";
        $saveAs .= $this->validSlug . '-';
        $saveAs .= $variation['width'] . 'x' . $variation['height'];
        $saveAs .= '.' . $this->file->getClientOriginalExtension();
        return $saveAs;
    }


}