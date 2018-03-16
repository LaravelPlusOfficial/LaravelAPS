<?php

namespace App\Services\Seo\Tools;


use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class MetaTags
{

    use TagHelpers;

    /**
     * @var array
     */
    protected $metas = [
        'seo_title'                  => null,
        'seo_description'            => null,
        'seo_url'                    => null,
        'seo_image'                  => null,
        'og_type'                    => null,
        'article_section'            => null,
        'article_author'             => null,
        'published_at'               => null,
        'updated_at'                 => null,
        'link_next'                  => null,
        'link_prev'                  => null,
        'canonical'                  => null,
        'author_name'                => null,
        'twitter_creator'            => null,
        'article_author_google_plus' => null,
        'robots'                     => null,
    ];

    /**
     * @var array
     */
    protected $with = [];

    /**
     * @var View
     */
    protected $view;
    /**
     * @var array
     */
    protected $options;


    /**
     * MetaTags constructor.
     * @param View  $view
     * @param array $options
     */
    public function __construct(View $view, $options = [])
    {
        $this->view = $view;

        $this->options = $options;

        $this->setMetaOverrides();

        if (method_exists($this, 'before')) {
            $this->{'before'}();
        }

        $this->metanateView();
    }

    /**
     * @return $this
     */
    public function metanateView()
    {
        foreach ($this->metas as $meta => $value) {

            $method = Str::camel($meta);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }

        }

        return $this;
    }

    /**
     * @return View
     */
    public function getMetanatedView()
    {
        return $this->view->with($this->with);
    }

    /**
     * Set Meta overrides to be used while setting up meta
     */
    protected function setMetaOverrides()
    {
        $this->metas = array_merge($this->metas, isset($this->options['metas']) ? $this->options['metas'] : []);
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    protected function addToWith($key, $value)
    {
        $this->with[$key] = $value;

        return $this;
    }

}