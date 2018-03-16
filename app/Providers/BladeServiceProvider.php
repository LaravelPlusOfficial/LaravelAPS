<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Blade::component('components.alert', 'alert');

        Blade::component('components.forms.form', 'form');
        Blade::component('components.forms.input', 'input');
        Blade::component('components.forms.select', 'select');
        Blade::component('components.forms.checkbox', 'checkbox');
        Blade::component('components.forms.radio', 'radio');
        Blade::component('components.forms.textarea', 'textarea');
        Blade::component('components.forms.button', 'button');

        // Admin Components
        Blade::component('admin.components.page-title', 'pageTitle');

    }
}
