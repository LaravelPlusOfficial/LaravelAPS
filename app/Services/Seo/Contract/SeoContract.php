<?php

namespace App\Services\Seo\Contract;


use Illuminate\Contracts\View\View;

interface SeoContract
{
    /**
     * @param View $view
     * @param array $options
     * @return View
     */
    public function setMetaTags(View $view, $options = []);
}