<?php

namespace App\Services\Seo;


use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use App\Services\Seo\Contract\SeoContract;
use App\Services\Seo\Tools\MetaTagsForBlog;
use App\Services\Seo\Tools\MetaTagsForPage;
use App\Services\Seo\Tools\MetaTagsForPost;
use App\Services\Seo\Tools\MetaTagsForArchive;

class Seo implements SeoContract
{

    /**
     * @param View $view
     * @param array $options
     * @return View
     */
    public function setMetaTags(View $view, $options = [])
    {

        $method = Str::camel("set {$options['type']} Meta");

        if (method_exists($this, $method)) {
            return $this->$method($view, $options);
        }

        return $view;

    }

    /**
     * @param $view
     * @param $options
     * @return MetaTagsForPost|View
     */
    public function setSinglePostMeta($view, $options)
    {
        return (new MetaTagsForPost($view, $options))->getMetanatedView();
    }

    public function setArchiveMeta($view, $options)
    {
        return (new MetaTagsForArchive($view, $options))->getMetanatedView();
    }

    public function setPageMeta($view, $options)
    {
        return (new MetaTagsForPage($view, $options))->getMetanatedView();
    }

    public function setBlogMeta($view, $options)
    {
        return (new MetaTagsForBlog($view, $options))->getMetanatedView();
    }

}