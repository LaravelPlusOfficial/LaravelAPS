<?php

namespace App\Http\Requests;


trait RequestType
{

    /**
     * @return bool
     */
    protected function requestToCreate(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * @return bool
     */
    protected function requestToUpdate(): bool
    {
        return $this->method() === 'PUT' || $this->method() === 'PATCH';
    }

    /**
     * @return bool
     */
    protected function requestToDelete(): bool
    {
        return $this->method() === 'DELETE';
    }
}