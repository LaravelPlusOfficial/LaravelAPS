<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $guarded = [];

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public function getCountriesList()
    {
        return $this->orderBy('name', 'asc')->pluck('name', 'id');
    }

}
