<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Services\Taxonomy\Contract\TaxonomyContract;

class CategoriesController extends Controller
{

    public function index(TaxonomyContract $taxonomy)
    {
        $this->authorize('manage', Category::class);

        if( request()->expectsJson() ) {
            return $taxonomy->getThreadedCategories();
        }

        return view('admin.categories.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CategoryRequest $request)
    {
        $this->authorize('manage', Category::class);

        return $request->persist();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return Category|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('manage', Category::class);

        return $request->update($category);
    }

    /**
     * Remove the specified resource from storage.
     * @param CategoryRequest $request
     * @param $category
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(CategoryRequest $request, $category)
    {
        $this->authorize('manage', Category::class);

        return $request->delete($category);
    }
}
