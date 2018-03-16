<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->can('manage.pages')) {
            $pages = Page::latest()->with(['author', 'featuredImage'])->paginate(20);
        } else {
            $pages = Page::where('author_id', \Auth::id())
                ->latest()
                ->with(['author', 'featuredImage'])
                ->paginate(20);
        }

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('manage.pages', Page::class);

        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(PostRequest $request)
    {
        $this->authorize('manage.pages', Page::class);

        return $request->persistPage();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $page
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($page)
    {
        $page = Page::whereId($page)->with('featuredImage', 'metas')->firstOrFail();

        $this->authorize('manage.pages', $page);

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param $page
     * @return Page|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(PostRequest $request, Page $page)
    {
        $this->authorize('manage.pages', $page);

        return $request->updatePage($page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Page $page)
    {
        $this->authorize('manage.pages', $page);

        if ($page->delete()) {

            $page->metas()->delete();

            if (request()->wantsJson()) {
                return response("Page deleted", 200);
            }

            return redirect()->route('admin.page.index');

        }

        return response("Error", 500);
    }
}
