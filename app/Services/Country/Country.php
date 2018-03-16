<?php

namespace App\Services\Country;


use App\Services\Country\Contract\CountryContract;

class Country implements CountryContract
{

    public function getCountries()
    {
        return \Cache::remember(md5('countries'), 43200, function () {

            return \App\Models\Country::get()->toArray();

        });


    }
}