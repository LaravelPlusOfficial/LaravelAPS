<?php

namespace App\Http\Controllers\App;

use App\Models\Post;
use App\Scopes\PostScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Seo\Contract\SeoContract;

class SearchController extends Controller
{

    public function index(Request $request, SeoContract $seo)
    {

        if (!$request->q) {
            $view = view('app.search.index');

            return $seo->setMetaTags($view, [
                'type'  => 'page',
                'metas' => [
                    'seo_title'       => "Search",
                    'seo_description' => "Search page " . config('app.name')
                ]
            ]);
        }

        $posts = null;

        if ($request->q) {

            $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];

            $term = str_replace($reservedSymbols, '', $request->q);

            // Enable withoutGlobalScope to include pages
            //$posts = Post::withoutGlobalScope(PostScope::class)
            $posts = Post::latest()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', '%' . $term . '%')
                        ->orWhere('body', 'like', '%' . $term . '%')
                        ->orWhereHas('category', function ($query) use ($term) {
                            $query->where('name', 'like', '%' . $term . '%');
                        })
                        ->orWhereHas('tags', function ($query) use ($term) {
                            $query->where('name', 'like', '%' . $term . '%');
                        })
                        ->orWhereHas('author', function ($query) use ($term) {
                            $query->where('name', 'like', '%' . $term . '%');
                        });
                })->with('featuredImage', 'category', 'tags', 'author')->paginate(30);

            $view = view('app.search.index', compact('posts'))->with(['term' => $term]);

            return $seo->setMetaTags($view, [
                'type'  => 'page',
                'metas' => [
                    'seo_title'       => "Search result",
                    'seo_description' => "Search term for " . config('app.name'),
                ]
            ]);

        }

        abort(404);

    }

}
