<?php

namespace App\Services\Sitemap;


use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Carbon;
use App\Services\Sitemap\Contract\SitemapContract;

class Sitemap implements SitemapContract
{
    protected $sitemap;

    protected $pagesUrl = [
        'policy',
        'terms',
        'contact',
    ];

    /**
     * SitemapGenerator constructor.
     */
    public function __construct()
    {
        $this->sitemap = \App::make("sitemap");

        $this->sitemap->setCache(config('sitemap.cache_key'), 24 * 60);
    }

    /**
     * Render sitemap to screen
     */
    public function render()
    {
        if (!$this->sitemap->isCached()) {
            $this->makeSitemap();
        }

        return $this->sitemap->render('xml');
    }

    /**
     * Store sitemap xml file to public folder
     * @return mixed
     */
    public function store()
    {
        $this->makeSitemap();

        return $this->sitemap->store('xml', 'sitemap');
    }

    protected function makeSitemap()
    {
        $this->addPosts()->addPages();
    }

    /**
     * Add pages to sitemap
     *
     * @return $this
     */
    private function addPages()
    {
        $pages = Page::latest()->select('slug', 'updated_at', 'id', 'featured_image_id')
            ->with([
                'featuredImage' => function ($q) {
                    $q->select('id', 'variations');
                }
            ])
            ->get();

        $pages->each(function ($page) {

            $images = [];
            if ($page->featuredImage) {
                $images = [
                    ['url' => url($page->featuredImage->variations['large']['path']), 'title' => $page->title],
                ];
            }
            $this->sitemap->add(
                route('post.show', $page->slug),
                $page->updated_at->toAtomString(),
                0.8,
                'daily',
                $images
            );
        });

        return $this;

//        $this->sitemap->add(\URL::to('/'), Carbon::now(), '1', 'daily');
//
//
//        foreach ($this->pagesUrl as $pageUrl) {
//
//
//            $dt = Carbon::now();
//
//            $dt->timestamp(filemtime(resource_path("views/app/pages/{$pageUrl}.blade.php")));
//
//            $this->sitemap->add(
//                url($pageUrl),
//                $dt->toAtomString(),
//                0.6,
//                'monthly'
//            );
//
//        }
//
//        return $this;
    }

    /**
     * Add posts to sitemap
     *
     * @return $this
     */
    private function addPosts()
    {
        $posts = Post::latest()->publish()->select('slug', 'updated_at', 'id', 'featured_image_id')
            ->with([
                'featuredImage' => function ($q) {
                    $q->select('id', 'variations');
                }
            ])
            ->get();

        $posts->each(function ($post) {

            $images = [];
            if ($post->featuredImage) {
                $images = [
                    ['url' => url($post->featuredImage->variations['large']['path']), 'title' => $post->title],
                ];
            }
            $this->sitemap->add(
                route('post.show', $post->slug),
                $post->updated_at->toAtomString(),
                0.8,
                'daily',
                $images
            );
        });

        return $this;
    }
}