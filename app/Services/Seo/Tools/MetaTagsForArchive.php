<?php

namespace App\Services\Seo\Tools;


use Illuminate\Support\Str;
use App\Services\Seo\Contract\MetaTagsContract;
use App\Services\Taxonomy\Contract\TaxonomyContract;

class MetaTagsForArchive extends MetaTags implements MetaTagsContract
{

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $subType;

    protected $paginator;

    /**
     *
     */
    public function before()
    {
        $this->setTypes();

        $this->paginator = $this->options['paginator'];
    }

    /**
     *
     */
    public function setTypes()
    {
        $this->type = $this->options['main_type'];

        $this->subType = isset($this->options['sub_type']) ? $this->options['sub_type'] : null;
    }

    /**
     * @param null $value
     */
    protected function seoTitle($value = null)
    {
        $this->addTitle($value ?: ucwords($this->subType ?? $this->type), true);
    }

    /**
     * @param null $value
     */
    protected function seoDescription($value = null)
    {
        try {
            $description = ucfirst(trim($this->getDescription(resolve(TaxonomyContract::class))));
        } catch (\Exception $e) {
            $description = null;
        }

        $this->addDescription($value ?: $description, true);
    }

    /**
     * @param null $value
     */
    protected function seoUrl($value = null)
    {
        $this->view->getFactory()->startSection('seo_url', $value ? $value : request()->url());
    }

    /**
     * @param null $value
     */
    protected function articleSection($value = null)
    {
        $this->view->getFactory()
            ->startSection(
                'article_section',
                $value ? $value : ucwords(str_replace('.', ' ', $this->subType ?? $this->type))
            );
    }

    protected function linkNext($value = null)
    {
        $this->addNextLink($value ?: $this->paginator->nextPageUrl());
    }

    protected function linkPrev($value = null)
    {
        $this->addPrevLink($value ?: $this->paginator->previousPageUrl());
    }

    /**
     * @param TaxonomyContract $tc
     * @return null
     */
    protected function getDescription(TaxonomyContract $tc)
    {
        $possibleMethod = $this->guessMethod(); // getCategories(), getTags()

        if ($subType = $this->hasSubType()) {

            if (method_exists($tc, $possibleMethod)) {

                return $this->getDescriptionOfSubType($tc, $possibleMethod, $subType);

            }

            return null;

        }

        if (!$this->hasSubType()) {

            if ($description = $this->getDescriptionFromPostTypes($tc)) {
                return $description;
            }

        }

        return null;
    }

    /**
     * Guess method based on Taxonomy type or Post Type
     * for Taxonomy it will return 'getCategories' || 'getTags' etc...
     * for Post Type it will return 'getTutorials' || 'getPackages' etc...
     *
     * @return string
     */
    protected function guessMethod()
    {
        return Str::camel("get " . Str::plural($this->type));
    }

    /**
     * Check if query has sub type
     * e.g. www.example.com/category/laravel
     * www.example.com/isType/isSubType
     *
     * @return mixed
     */
    protected function hasSubType()
    {
        return (bool) $this->subType;
    }

    /**
     * If Sub types has a description get that
     * Types are like Category, Tag, Tutorials, Packages etc...
     * Sub types are Like Categories - Laravel, Vue etc, Tags - Vue2.0, Laravel5.6 etc..
     *
     * @param TaxonomyContract $tc
     * @param $possibleMethod
     * @param $subType
     * @return null
     */
    protected function getDescriptionOfSubType(TaxonomyContract $tc, $possibleMethod, $subType)
    {
        $subType = array_first($tc->$possibleMethod(), function ($value, $key) use ($subType) {
            return $value['slug'] == $subType;
        });

        return $subType ? $subType['description'] : null;
    }

    /**
     * Check if requested type is a kind of Post type
     * e.g. www.example.com/tutorials
     * e.g. www.example.com/isPostType
     *
     * And return its description
     *
     * @param TaxonomyContract $tc
     * @return null
     */
    protected function getDescriptionFromPostTypes(TaxonomyContract $tc)
    {
        $type = $this->type;

        // If sub type not present then try to find from types
        $postType = array_first($tc->getPostTypes(), function ($value, $key) use ($type) {
            return $value['slug'] == $type;
        });

        return $postType ? $postType['description'] : null;
    }

}