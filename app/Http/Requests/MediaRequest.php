<?php

namespace App\Http\Requests;

//use App\Factory\Media\MediaUploader;
use App\Models\Media;
use App\Services\Media\ImageUploader;
use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    use RequestType;

    /**
     * @var array
     */
    protected $validMimeTypes = [
        'image/gif',
        "image/png",
        "image/jpeg"
    ];
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * MediaRequest constructor.
     *
     * @param Media $model
     * @param ImageUploader $imageUploader
     */
    public function __construct(Media $model, ImageUploader $imageUploader)
    {
        parent::__construct();

        $this->model = $model;

        $this->imageUploader = $imageUploader;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->requestToCreate()) {
            return ["file" => ["required", "mimetypes:" . implode(",", $this->validMimeTypes)]];
        }

        if ($this->requestToUpdate()) {
            return ['id' => 'required|exists:media,id'];
        }

        if ($this->requestToDelete()) {
            return ['id' => 'required|exists:media,id'];
        }

    }

    /**
     * Error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.max' => 'File size should be less then 10MB'
        ];
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadMedia()
    {
        // Let find the type of file we are dealing with
        // If its video or image, no need to do anything else
        // but, if its pdf, word, or excel -> then we will treat them as a document and proceed with methodCall
        $mimeType = explode('/', $this->file->getClientMimeType())[0];

        $method = $mimeType == 'application' ? 'storeDocument' : 'store' . ucfirst($mimeType);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        return response("Invalid file type", 500);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function storeImage()
    {
        return $this->imageUploader->upload($this->file);
    }

    public function removeMedia($id)
    {
        $media = $this->model->where('id', $id)->firstOrFail();

        $disk = \Storage::disk(config('aps.media.disk'));

        foreach ($media->variations as $variation) {
            if ($disk->exists($variation['path'])) {
                $disk->delete($variation['path']);
            }
        }

        if ($media->delete()) {
            return response("Media deleted", 200);
        }

        return response("Error while deleting media", 500);
    }
}
