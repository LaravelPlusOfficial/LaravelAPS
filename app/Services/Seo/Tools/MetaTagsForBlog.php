<?php

namespace App\Services\Seo\Tools;


use App\Services\Seo\Contract\MetaTagsContract;

class MetaTagsForBlog extends MetaTags implements MetaTagsContract
{

    protected $paginator;

    protected function before()
    {
        $this->paginator = $this->options['paginator'];
    }

    /**
     * @param null $value
     */
    protected function seoTitle($value = null)
    {
        $value ? $this->addTitle($value, true) : $this->addTitle('Blog');
    }

    /**
     * @param null $value
     */
    protected function seoUrl($value = null)
    {
        $this->addUrl($value ?: request()->url());
    }

    /**
     * @param null $value
     */
    protected function seoDescription($value = null)
    {
        $value ? $this->addDescription($value, true) : $this->addDescription('');
    }

    /**
     * @param null $value
     * @throws \Exception
     */
    protected function seoImage($value = null)
    {
        $url = url(setting('blog_image', mix('/site/defaults/site-share.png')));

        $this->addImage($value ?: removeQueryFromUrl($url));
    }

    protected function linkNext($value = null)
    {
        $this->addNextLink($value ?: $this->paginator->nextPageUrl());
    }

    protected function linkPrev($value = null)
    {
        $this->addPrevLink($value ?: $this->paginator->previousPageUrl());
    }

}