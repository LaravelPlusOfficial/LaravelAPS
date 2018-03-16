<?php

namespace App\Services\Taxonomy;


use App\Models\Tag;
use App\Models\Category;
use App\Services\Taxonomy\Contract\TaxonomyContract;
use Illuminate\Support\Facades\Cache;

class Taxonomy implements TaxonomyContract
{

    public function getCategories()
    {
        return Cache::rememberForever(md5('categories'), function () {
            return Category::get()->toArray();
        });
    }

    public function getTags()
    {
        return Cache::rememberForever(md5('tags'), function () {
            return Tag::get()->toArray();
        });
    }

    public function getThreadedCategories()
    {
        return Cache::rememberForever(md5('threaded.categories'), function () {
            return Category::whereParentId(null)->with('children')->get()->toArray();
        });
    }

    public function clearCache()
    {
        Cache::forget(md5('categories'));
        Cache::forget(md5('tags'));
        Cache::forget(md5('threaded.categories'));
    }
}