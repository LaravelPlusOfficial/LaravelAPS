<?php

use App\Models\Setting;
use Faker\Generator as Faker;

$factory->define(Setting::class, function (Faker $faker) {
    $group = 'general';

    $label = 'Setting label';

    return [
        'group'      => $group,
        'label'      => $label,
        'value'      => "",
        'type'       => 'input',
        'options'    => [],
        'help'       => null,
        'help_label' => null
    ];
});
