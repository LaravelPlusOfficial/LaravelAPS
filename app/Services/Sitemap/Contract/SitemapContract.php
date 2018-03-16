<?php
namespace App\Services\Sitemap\Contract;


interface SitemapContract
{

    public function render();

    public function store();
}