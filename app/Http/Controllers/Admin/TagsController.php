<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;

class TagsController extends Controller
{

    public function index(Request $request)
    {
        if (request()->expectsJson()) {

            $query = $request->get('search');

            if (!$query || empty($query)) {
                return Tag::get();
            }

            return Tag::where('name', 'LIKE', '%' . $query . '%')->select(['id', 'slug', 'name'])->get();

        }

        return view('admin.tags.index');
    }

    public function tagByName($name)
    {
        return Tag::whereName($name)->firstOrFail();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(TagRequest $request)
    {
        return $request->persist();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TagRequest $request
     * @param Tag $tag
     * @return Tag|\Illuminate\Contracts\Routing\ResponseFactory|null|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $this->authorize('manage', $tag);

        return $request->update($tag);
    }

    /**
     * Remove the specified resource from storage.
     * @param Tag $tag
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('manage', $tag);

        if ($tag->delete()) {
            return response("Tag deleted", 200);
        }

        return response("Error", 500);
    }
}
