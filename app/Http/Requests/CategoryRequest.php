<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Services\Taxonomy\Contract\TaxonomyContract;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    use RequestType;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $name = $this->name;

        if ($this->requestToCreate()) {
            // New Category
            return [
                'name'        => [
                    'required',
                    'max:' . setting('seo_title_length', 70),
                    Rule::unique('categories')
                        ->where(function ($query) use ($name) {
                            $query->where('name', $name);
                        })
                ],
                'description' => 'required|max:' . setting('seo_description_length', 160),
                'parent_id'   => 'nullable:exists:categories,id'
            ];
        }

        if ($this->requestToUpdate()) {
            // Updating
            $slug = $this->getCategorySlug();

            return [
                'slug'        => [
                    'required',
                    'max:' . setting('seo_title_length', 160),
                    Rule::unique('categories')
                        ->where(function ($query) use ($slug) {
                            $query->where('slug', $slug);
                        })
                        ->ignore(
                            $this->route()->parameter('category')->id, 'id'
                        )
                ],
                'name'        => [
                    'required',
                    'max:' . setting('seo_title_length', 160),
                    Rule::unique('categories')
                        ->where(function ($query) use ($name) {
                            $query->where('name', $name);
                        })
                        ->ignore(
                            $this->route()->parameter('category')->id, 'id'
                        )
                ],
                'description' => 'required|max:' . setting('seo_description_length', 160),
                'parent_id'   => 'nullable:exists:categories,id'
            ];
        }

        if ($this->requestToDelete()) {
            return [];
        }


    }

    /**
     * Persist Tag to database
     *
     * @return mixed
     */
    public function persist()
    {
        $category = Category::create([
            'name'        => $this->name,
            'description' => $this->description,
            'parent_id'   => $this->parent_id
        ]);

        $this->resetCache();

        return Category::whereId($category->id)->with('children')->first();
    }

    /**
     * Update tag
     *
     * @param Category $category
     *
     * @return Category|\Illuminate\Http\RedirectResponse
     */
    public function update(Category $category)
    {
        if ($category->slug === 'uncategorized') {
            abort(403);
        }

        $data = array_merge($this->only('name', 'description', 'parent_id'), ['slug' => $this->getCategorySlug()]);

        if ($category->update($data)) {

            $this->resetCache();

            if ($this->expectsJson()) {
                return $category->fresh(['children']);
            }

            return redirect()->route('admin.category.show', $category->fresh()->slug);

        }

        if ($this->expectsJson()) {
            return response('Something went wrong while updating category', 500);
        }

        return back()
            ->withErrors('Something went wrong while updating category')
            ->with([
                'flash' => 'Not able to update category at this time',
                'level' => 'error'
            ]);
    }

    /**
     * Delete tag from database
     *
     * @param $categoryId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($categoryId)
    {
        $category = Category::whereId($categoryId)->with('children')->withCount('posts')->firstOrFail();

        if ($response = $this->categoryNotDeleteAble($category)) {
            return $response;
        }

        //$categoryName = $category->name;

        if ($category->delete()) {

            $this->resetCache();

            return response("Category {$category->name} have been deleted", 200);
        }

        return response("Error! while deleting", 500);
    }


    /**
     * @return string
     */
    protected function getCategorySlug(): string
    {
        return str_slug($this->slug);
    }

    protected function resetCache()
    {
        resolve(TaxonomyContract::class)->clearCache();
    }

    protected function categoryNotDeleteAble($category)
    {
        if ($this->categoryHasPosts($category) || $this->categoryHasChildren($category)) {
            return response("Category {$category->name} not deletable", 500);
        }
    }

    protected function categoryHasPosts($category)
    {
        return $category->posts_count > 0;
    }

    protected function categoryHasChildren($category)
    {
        return (bool)$category->children->count();
    }

}