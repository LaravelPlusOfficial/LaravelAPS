<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            // New Tag
            return [
                'name'        => [
                    'required',
                    Rule::unique('tags')
                        ->where(function ($query) use ($name) {
                            $query->where('name', $name);
                        })
                ],
                'description' => 'max:' . setting('seo_description_length', 160),
            ];
        }

        if ($this->requestToUpdate()) {

            $slug = $this->getTagSlug();

            return [
                'slug'        => [
                    'required',
                    Rule::unique('tags')
                        ->where(function ($query) use ($slug) {
                            $query->where('slug', $slug);
                        })
                        ->ignore($this->route()->parameter('tag')->id, 'id')
                ],
                'name'        => [
                    'required',
                    Rule::unique('tags')
                        ->where(function ($query) use ($name) {
                            $query->where('name', $name);
                        })
                        ->ignore($this->route()->parameter('tag')->id, 'id')
                ],
                'description' => 'max:' . setting('seo_description_length', 160),
            ];
        }

    }

    /**
     * Persist Tag to database
     *
     * @return mixed
     */
    public function persist()
    {
        $tag = Tag::create([
            'name'        => $this->name,
            'description' => $this->description,
        ]);

        return $tag->fresh();
    }

    /**
     * Update tag
     * @param Tag $tag
     * @return Tag|\Illuminate\Contracts\Routing\ResponseFactory|null|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Tag $tag)
    {

        $data = array_merge(
            $this->only('name', 'description'),
            ['slug' => $this->getTagSlug()]
        );

        if ($tag->update($data)) {
            return $tag->fresh();
        }

        return response('Error', 500);

    }

    /**
     * @return string
     */
    protected function getTagSlug(): string
    {
        return str_slug($this->slug);
    }
}