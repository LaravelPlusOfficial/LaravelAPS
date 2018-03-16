<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\Sitemap\Contract\SitemapContract;

class SitemapsController extends Controller
{
    /**
     * @param SitemapContract $sitemap
     * @return mixed
     */
    public function index(SitemapContract $sitemap)
    {
        return $sitemap->render();
    }

    /**
     * @param SitemapContract $sitemap
     * @return mixed
     */
    public function store(SitemapContract $sitemap)
    {
        return $sitemap->store();
    }
}
