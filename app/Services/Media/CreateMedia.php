<?php
namespace App\Services\Media;

use App\Models\Media;

class CreateMedia
{

    /**
     * @var array
     */
    private $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function create()
    {
        return Media::create($this->data);
    }
    
}