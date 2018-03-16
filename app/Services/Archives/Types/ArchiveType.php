<?php

namespace App\Services\Archives\Types;


use App\Models\Post;
use App\Services\Seo\Contract\SeoContract;
use App\Services\Archives\Contract\ArchiveTypeContract;

abstract class ArchiveType implements ArchiveTypeContract
{

    protected $seo;

    protected $view;

    protected $builder;

    protected $perPage;

    protected $taxonomyTable;

    protected $type;

    protected $subType;

    public function __construct()
    {
        $this->seo = resolve(SeoContract::class);

        $this->perPage = request()->perPage ?: setting('post_default_pagination_count', 10);
    }

    protected function getPosts($taxonomyTable = null)
    {
        $this->builder = Post::latest();

        if ($taxonomyTable) {

            $this->taxonomyTable = $taxonomyTable;

            $this->builder->whereHas($this->taxonomyTable, function ($q) {
                $q->where('slug', $this->subType);
            });
        }

        $this->builder->with(['category', 'metas']);

        $this->builder->fetchAll();

        return $this;
    }

    protected function paginate()
    {
        return $this->builder->simplePaginate($this->perPage);
    }

    protected function metanateView($options)
    {
        return $this->seo->setMetaTags($this->view, $options);
    }

    public function getView($view = null)
    {

        if ($view && view()->exists("app.archives.{$view}.posts")) {

            return view("app.archives.{$view}.posts");

        }

        if (view()->exists("app.archives.{$this->taxonomyTable}.posts")) {

            return view("app.archives.{$this->taxonomyTable}.posts");

        }

        if (view()->exists("app.archives.{$this->subType}.posts")) {

            return view("app.archives.{$this->subType}.posts");

        }

        return view("app.archives.posts");
    }

    /**
     * @param mixed $type
     * @return ArchiveType
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $subType
     * @return ArchiveType
     */
    public function setSubType($subType)
    {
        $this->subType = $subType;
        return $this;
    }

}