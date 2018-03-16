<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\PublishPostToSocialMedia;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Services\Taxonomy\Contract\TaxonomyContract;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->can('manage.posts')) {
            $posts = Post::latest()
                ->with(['category', 'author', 'featuredImage'])->paginate(20);
        } else {
            $posts = Post::where('author_id', \Auth::id())
                ->latest()
                ->with(['category', 'author', 'featuredImage'])
                ->paginate(20);
        }

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param TaxonomyContract $tx
     * @return \Illuminate\Http\Response
     */
    public function create(TaxonomyContract $tx)
    {
        $categories = $tx->getThreadedCategories();

        $postTypes = config('aps.post.types');

        $postFormats = config('aps.post.formats');

        return view('admin.posts.create', compact('categories', 'postTypes', 'postFormats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostRequest $request)
    {
        return $request->persistPost();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param TaxonomyContract $tx
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id, TaxonomyContract $tx)
    {
        $post = Post::whereId($id)
            ->with([
                'category',
                'tags',
                'featuredImage',
                'metas'
            ])->firstOrFail();

        $this->authorize('manage', $post);

        $categories = $tx->getThreadedCategories();

        $postTypes = config('aps.post.types');

        $postFormats = config('aps.post.formats');

        return view('admin.posts.edit', compact('post', 'categories', 'postTypes', 'postFormats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param Post $post
     * @return Post
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('manage', $post);

        return $request->updatePost($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return bool
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $this->authorize('manage', $post);

        if ($post->delete()) {

            $post->metas()->delete();

            if (request()->wantsJson()) {
                return response("Post deleted", 200);
            }

            return redirect()->route('admin.post.index');

        }

        return response("Error", 500);
    }

    /**
     * @param $post
     * @param $provider
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function rePublishToSocialMedia($post, $provider)
    {
        $post = Post::whereId($post)->firstOrFail();

        $this->authorize('manage', $post);

        dispatch(new PublishPostToSocialMedia($post, true, $provider));

        return $post;
    }
}
