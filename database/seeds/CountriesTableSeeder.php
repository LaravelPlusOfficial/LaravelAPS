<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = static::getCountriesData();

        foreach ($countries as $country) {
            DB::table('countries')->insert(array(
                'capital' => ((isset($country['capital'])) ? $country['capital'] : null),
                'citizenship' => ((isset($country['citizenship'])) ? $country['citizenship'] : null),
                'country_code' => $country['country-code'],
                'currency' => ((isset($country['currency'])) ? $country['currency'] : null),
                'currency_code' => ((isset($country['currency_code'])) ? $country['currency_code'] : null),
                'currency_sub_unit' => ((isset($country['currency_sub_unit'])) ? $country['currency_sub_unit'] : null),
                'full_name' => ((isset($country['full_name'])) ? $country['full_name'] : null),
                'iso_3166_2' => $country['iso_3166_2'],
                'iso_3166_3' => $country['iso_3166_3'],
                'name' => $country['name'],
                'region_code' => $country['region-code'],
                'sub_region_code' => $country['sub-region-code'],
                'eea' => (bool)$country['eea'],
                'calling_code' => $country['calling_code'],
                'currency_symbol' => ((isset($country['currency_symbol'])) ? $country['currency_symbol'] : null),
                'flag' => ((isset($country['flag'])) ? $country['flag'] : null),
            ));
        }
    }

    public static function getCountriesData($sort = null)
    {
        $countries = json_decode(file_get_contents(__DIR__ . '/../SeedData/countries/countries-data.json'), true);

        //Sorting
        $validSorts = array(
            'capital',
            'citizenship',
            'country-code',
            'currency',
            'currency_code',
            'currency_sub_unit',
            'full_name',
            'iso_3166_2',
            'iso_3166_3',
            'name',
            'region-code',
            'sub-region-code',
            'eea',
            'calling_code',
            'currency_symbol',
            'flag'
        );

        if (!is_null($sort) && in_array($sort, $validSorts)){
            uasort($countries, function($a, $b) use ($sort) {
                if (!isset($a[$sort]) && !isset($b[$sort])){
                    return 0;
                } elseif (!isset($a[$sort])){
                    return -1;
                } elseif (!isset($b[$sort])){
                    return 1;
                } else {
                    return strcasecmp($a[$sort], $b[$sort]);
                }
            });
        }

        //Return the countries
        return $countries;
    }
}
