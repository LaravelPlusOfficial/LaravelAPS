<?php

namespace App\Services\Settings\Processing;


use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Media\ImageGenerator;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class ProcessImageSetting implements ProcessSetting
{

    /**
     * @var Request
     */
    protected $request;

    protected $image;

    protected $disk;

    protected $name;

    protected $config;

    /**
     * @var Setting
     */
    protected $setting;

    public function __construct(Setting $setting, Request $request)
    {
        $this->request = $request;

        $this->setting = $setting;

        $this->config = config("aps.media.{$setting->key}");

        if (!$this->config) {
            throw new \Exception("Dimension for lp.media.{$setting->key} doesn't exist");
        }

        $this->disk = Storage::disk(config('aps.media.disk'));

    }

    public function update()
    {
        if (!$this->request->{$this->setting->key}) {

            return $this->saveSetting(null);
        }

        if ($this->request->{$this->setting->key} instanceof UploadedFile) {

            $data = $this->uploadFile();

            $this->setting->value = $data['path'];

            $this->setting->info = $data;

            $this->setting->save();

        }

        return $this->setting;
    }

    protected function uploadFile()
    {

        $file = $this->request->file($this->setting->key);

        $imageGenerator = (new ImageGenerator($file, $this->disk))->setInterventionImage();

        $image = $imageGenerator->fit($this->config['width'], $this->config['height']);

        $time = Carbon::now()->timestamp;

        $this->name = md5(str_slug("{$this->setting->key} {$image->width()}x{$image->height()} " . $time, "-"));

        $saveAs = $this->getSaveAs($file->getClientOriginalExtension());

        $this->removePreviousImage($saveAs);

        try {
            return $imageGenerator->save($image, $saveAs, 'image');
        } catch (FileNotFoundException $e) {
        }

    }

    protected function saveSetting($value)
    {
        $this->setting->value = $value;

        $this->setting->info = null;

        $this->setting->save();

        return $this->setting;
    }

    protected function getSaveAs($extension)
    {
        return "{$this->config['folder']}/{$this->name}.{$extension}";
    }

    protected function removePreviousImage($location)
    {
        if ($this->disk->exists($location)) {
            $this->disk->delete($location);
        }
    }
}