<?php

namespace App\Http\Controllers\App;

use App\Models\Page;
use App\Models\Post;
use Sunra\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;
use App\Services\Archives\Contract\ArchiveContract;

class PostsController extends Controller
{
    /**
     * @var SeoContract
     */
    protected $seo;

    public function __construct(SeoContract $seo)
    {
        $this->seo = $seo;
    }


    public function index(ArchiveContract $archive)
    {
        return $archive->getResult('blog');
    }

    public function shortUrlShow($shortUrl)
    {
        $post = Post::where('short_url', $shortUrl)->select('id', 'slug')->firstOrFail();

        return $this->show($post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug)
    {

        if (method_exists($this, $slug)) {

            return $this->$slug();
        }

        if (Post::whereSlug($slug)->exists()) {

            return $this->getPostShow($slug);

        }

        if (Page::whereSlug($slug)->exists()) {

            return $this->getPageShow($slug);

        }

        abort(404);

    }

    protected function getPostShow($slug)
    {
        $post = Post::whereSlug($slug)
            ->withCount([
                'comments' => function ($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->with(['featuredImage', 'category', 'tags', 'author', 'metas'])->first();

        if ($post->isDraft() && !optional(request()->user())->owns($post, 'author_id')) {
            abort(404);
        }

        $view = view('app.posts.show')
            ->with([
                'post'     => $post,
                'prevPost' => Post::prevPost($post->id)->first(),
                'nextPost' => Post::nextPost($post->id)->first()
            ]);

        return $this->seo->setMetaTags($view, [
            'type' => 'singlePost',
            'post' => $post
        ]);
    }


    protected function getPageShow($slug)
    {
        $page = Page::whereSlug($slug)->with(['featuredImage', 'author', 'metas'])->first();

        $view = View::exists("app.pages.{$page->slug}") ? "app.pages.{$page->slug}" : "app.posts.show";

        $view = view($view);

        $view = $view->with(['page' => $page]);

        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => $page->title,
                'seo_description' => $page->excerpt
            ]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function icons()
    {
        $file = resource_path('views/layouts/common/svg-sprite.blade.php');

        $els = HtmlDomParser::str_get_html(file_get_contents($file))->find('symbol');

        $icons = [];

        foreach ($els as $el) {
            $icons[] = $el->getAttribute('id');
        }

        $view = view('app.pages.icons', compact('icons'));

        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Icons list',
                'seo_description' => 'Icon list used in Laravel plus website'
            ]
        ]);
    }

}


