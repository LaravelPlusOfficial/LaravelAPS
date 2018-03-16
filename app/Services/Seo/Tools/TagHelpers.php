<?php

namespace App\Services\Seo\Tools;


use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait TagHelpers
{

    /**
     * @param string $name
     * @param null   $value
     */
    protected function addSection($name = '', $value = null)
    {
        if ($value) {
            $this->view->getFactory()->startSection($name, $value);
        }
    }

    /**
     * @param      $title
     * @param bool $sendToWith
     */
    protected function addTitle($title, $sendToWith = false)
    {
        if ($title) {
            $this->addSection("seo_title", new HtmlString($this->title($title)));

            $this->addToWith('seoTitle', $title);

            if ($sendToWith) {
                $this->addToWith('pageTitle', $title);
            }
        }
    }

    /**
     * @param      $description
     * @param bool $sendToWith
     */
    protected function addDescription($description, $sendToWith = false)
    {
        if ($description) {
            $this->addSection("seo_description", $this->description($description));

            $this->addToWith('seoDescription', $description);

            if ($sendToWith) {
                $this->addToWith('pageDescription', $description);
            }
        }
    }

    /**
     * @param $url
     */
    protected function addUrl($url)
    {
        $this->view->getFactory()->startSection('seo_url', url($url));
    }

    protected function addOgType($type)
    {
        if ($type) {
            $this->view->getFactory()->startSection('og_type', $type);
        }
    }

    /**
     * @param $url
     */
    protected function addCanonical($url)
    {
        $this->view->getFactory()->startSection('canonical', url($url));
    }

    /**
     * @param $image
     */
    protected function addImage($image)
    {
        $this->view->getFactory()->startSection('seo_image', url($image));
    }

    /**
     * @param $value
     */
    protected function addArticleSection($value)
    {
        if ($value) {
            $this->view->getFactory()->startSection('article_section', $value);
        }
    }

    /**
     * @param $arthur
     */
    protected function addArticleAuthor($arthur)
    {
        if ($arthur) {
            $this->view->getFactory()->startSection('fb_author', $arthur);
            $this->view->getFactory()->startSection('article_author', $arthur);
        }
    }

    /**
     * @param $name
     */
    protected function addAuthorName($name)
    {
        if ($name) {
            $this->view->getFactory()->startSection('author_name', $name);
        }
    }

    /**
     * @param $date
     */
    protected function addPublishDate($date)
    {
        if ($date) {
            $this->view->getFactory()->startSection('published_at', $this->dateFormat($date));
        }
    }

    /**
     * @param $date
     */
    protected function addUpdateDate($date)
    {
        if ($date) {
            $this->view->getFactory()->startSection('updated_at', $this->dateFormat($date));
        }
    }

    /**
     * @param $link
     */
    protected function addNextLink($link)
    {
        if ($link) {
            $this->view->getFactory()->startSection('link_next', $link);
        }
    }

    /**
     * @param $link
     */
    protected function addPrevLink($link)
    {
        if ($link) {
            $this->view->getFactory()->startSection('link_prev', $link);
        }
    }

    /**
     * @param $username
     */
    protected function addTwitterCreator($username)
    {
        if ($username) {
            $this->view->getFactory()->startSection('twitter_creator', $username);
        }
    }

    protected function addArticleAuthorGooglePlus($author)
    {
        if ($author) {
            $this->view->getFactory()->startSection('article_author_google_plus', $author);
        }
    }

    protected function addRobots($robots)
    {
        if ($robots) {
            $this->view->getFactory()->startSection('robots', $robots);
        }
    }

    /**
     * @param Carbon $date
     * @return string
     */
    protected function dateFormat(Carbon $date)
    {
        return $date->toW3cString();
    }

    /**
     * @param string $title
     * @return string
     */
    protected function title($title = '')
    {
        return "{$title} " . setting('seo_default_title_separator', '&bull;') . " " . config('app.name');
    }

    /**
     * @param string $description
     * @return string
     */
    protected function description($description = '')
    {
        return Str::limit($description, setting('seo_description_length', 160), '');
    }

    protected function getMeta($meta)
    {
        if (isset($this->postMetas[$meta])) {
            return $this->postMetas[$meta];
        }

        return null;
    }
}