<?php

namespace App\Http\Requests;

use App\Jobs\PublishPostToSocialMedia;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    use RequestType;

    /**
     * @var array
     */
    protected $validMetas = [
        'description',
        'robots',
        'auto_post_facebook',
        'auto_post_twitter'
    ];

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
        if ($this->requestToCreate()) {
            return $this->allRules();
        }

        if ($this->requestToUpdate()) {

            $slug = $this->getPostSlug();

            $ignoreId = optional($this->route()->parameter('post'))->id ?: optional($this->route()->parameter('page'))->id;

            $rules = [
                'slug' => [
                    'required',
                    Rule::unique('posts')
                        ->where(function ($q) use ($slug) {
                            $q->where('slug', $slug);
                        })
                        ->ignore($ignoreId, 'id')]
            ];

            return $this->allRules() + $rules;
        }

    }

    /**
     * @return mixed
     */
    public function persistPost()
    {
        $data = $this->getData([
            'author_id'     => $this->user()->id,
            'category_slug' => $this->category_slug,
            'post_format'   => $this->post_format
        ]);

        $post = Post::create($data);

        if ($post->post_type == 'post') {
            $post->tags()->sync($this->tags);
        }

        $this->storeMetas($post);

        session()->flash('success', 'Post saved');

        dispatch(new PublishPostToSocialMedia($post->fresh(['featuredImage', 'metas'])));

        return $post;
    }

    /**
     * @return mixed
     */
    public function persistPage()
    {
        $data = $this->getData(['author_id' => $this->user()->id]);

        $page = Page::create($data);

        $this->storeMetas($page);

        dispatch(new PublishPostToSocialMedia($page));

        session()->flash('success', 'Page saved');

        return $page;

    }

    /**
     * @param Post $post
     * @return Post
     */
    public function updatePost(Post $post)
    {
        $data = $this->getData([
            'slug'          => $this->getPostSlug(),
            'category_slug' => $this->category_slug,
            'post_format'   => $this->post_format
        ]);

        if ($post->update($data)) {

            $post->tags()->sync($this->tags);

            $this->storeMetas($post);

            return $post->fresh(['category', 'tags', 'featuredImage', 'metas']);

        }

        return response("Error", 500);
    }

    /**
     * @param Page $page
     * @return Page|\Illuminate\Contracts\Routing\ResponseFactory|null|\Symfony\Component\HttpFoundation\Response
     */
    public function updatePage(Page $page)
    {
        $data = $this->getData(['slug' => $this->getPostSlug()]);

        if ($page->update($data)) {

            $this->storeMetas($page);

            return $page->fresh(['featuredImage', 'metas']);
        }

        return response("Error", 500);

    }

    /**
     * @param $model
     */
    protected function storeMetas($model)
    {
        // dd('sdbv');

        foreach ($this->getValidMetas() as $metaKey => $metaValue) {

            $model->metas()->updateOrCreate([
                "key"           => $metaKey,
                "metaable_id"   => $model->id,
                "metaable_type" => get_class($model)
            ], ["value" => $metaValue]);

        }
    }

    /**
     * @return string
     */
    protected function getPostSlug(): string
    {
        return str_slug($this->slug);
    }

    /**
     * @return array
     */
    protected function allRules()
    {
        return [
            'title'             => 'required',
            'tags'              => 'array',
            'tags.*'            => 'exists:tags,id',
            'publish_at'        => 'nullable|date_format:"Y-m-d H:i:s"',
            'featured_image_id' => 'nullable|exists:media,id',
            'metas.description' => 'nullable',
            'metas.no_index'    => 'nullable|boolean',
            'metas.no_follow'   => 'nullable|boolean',
            'post_type'         => [
                'required',
                'max:255',
                Rule::in(config('aps.post.types'))
            ],
            'body'              => 'required_unless:post_type,page',
            'category_slug'     => 'nullable|required_if:post_type,post|exists:categories,slug',
            'post_format'       => [
                'required_with:post_type,post',
                'max:255',
                Rule::in(config('aps.post.formats'))
            ]
        ];
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function getData($fields = [])
    {
        return array_merge($this->only([
            'title',
            'body',
            'excerpt',
            'publish_at',
            'featured_image_id',
            'post_type'
        ]), $fields);
    }

    /**
     * @return array
     */
    protected function getValidMetas()
    {
        $providedMetas = $this->only('metas');

        $metas = $providedMetas && count($providedMetas) > 0 ? $providedMetas['metas'] : [];

        return array_only($metas, $this->validMetas);
    }

}
