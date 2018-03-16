<?php

namespace App\Services\Seo\Tools;


use App\Services\Seo\Contract\MetaTagsContract;

class MetaTagsForPage extends MetaTags implements MetaTagsContract
{

    /**
     * Title meta tag
     *
     * @param null $value
     */
    protected function seoTitle($value = null)
    {
        $value ? $this->addTitle($value, true) : null;
    }

    /**
     * Title meta tag
     *
     * @param null $value
     */
    protected function seoDescription($value = null)
    {
        $value ? $this->addDescription($value) : null;
    }

    protected function canonical($value = null)
    {
        $this->addCanonical($value ?: request()->url());
    }

}