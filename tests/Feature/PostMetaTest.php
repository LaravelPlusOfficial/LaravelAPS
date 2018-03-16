<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostMetaTest extends TestCase
{

    use RefreshDatabase;

    protected $validMetaArray;

    public function setUp()
    {
        parent::setUp();

        $this->validMetaArray = [
            'description' => 'meta description',
            'no_index' => true,
            'no_follow' => true,
        ];
    }


    /** @test */
    public function a_post_can_have_metas()
    {
        $this->withoutExceptionHandling();

        $this->signInConfirmed();

        $postArray = $this->makePost();

        $response = $this->post(route('admin.post.store'), $postArray + ['metas' => $this->validMetaArray]);

        $post = Post::whereId(json_decode($response->getContent(), 1)['id'])->with('metas')->firstOrFail();

        foreach ($post->metas as $meta) {
            $this->assertEquals($this->validMetaArray[$meta->key], $meta->value);
        }

    }

    /** @test */
    public function only_valid_meta_can_be_stored_into_database()
    {

        $this->withoutExceptionHandling();

        $this->signInConfirmed();

        $postArray = $this->makePost();

        $response = $this->post(route('admin.post.store'), $postArray + ['metas' => $this->validMetaArray]);

        $post = json_decode($response->getContent(), 1);

        $this->checkIfDatabaseHasMeta('description', "\"meta description\"", $post['id']);

        $this->checkIfDatabaseMissingMeta("invalid_key", "\"Invalid value\"", $post['id']);
    }

    /** @test */
    public function post_meta_should_be_updateable()
    {
        $this->withoutExceptionHandling();

        $this->signInConfirmed();

        $postArray = $this->makePost();

        $response = $this->post(route('admin.post.store'), $postArray + ['metas' => $this->validMetaArray]);

        $post = json_decode($response->getContent(), 1);

        $categories = $this->create(Category::class, [], 2)->pluck('id')->toArray();

        $newPostMeta = [
            'description' => 'new meta description',
            'no_index' => false,
            'no_follow' => false,
        ];

        $data = array_merge($post, ['categories' => $categories], ['metas' => $newPostMeta]);

        $this->patch(route('admin.post.update', $post['id']), $data);

        $post = Post::whereId($post['id'])->with('metas')->firstOrFail();

        foreach ($post->metas as $meta) {
            $this->assertEquals($newPostMeta[$meta->key], $meta->value, "{$newPostMeta[$meta->key]} not equal to {$meta->value}");
        }

    }

    /** @test */
    public function on_post_delete_metas_should_also_be_removed_on_post_delete_metas_should_also_be_removed()
    {
        $this->withoutExceptionHandling();

        $this->signInConfirmed();

        $postArray = $this->makePost();

        $response = $this->post(route('admin.post.store'), $postArray + ['metas' => $this->validMetaArray]);

        $post = json_decode($response->getContent(), 1);

        $this->checkIfDatabaseHasMeta("description", "\"meta description\"", $post['id']);

        $this->delete(route('admin.post.destroy', $post['id']))
            ->assertRedirect(route('admin.post.index'));

        $this->checkIfDatabaseMissingMeta("description", "\"meta description\"", $post['id']);

        $this->checkIfDatabaseMissingMeta("keywords", "\"meta keywords\"", $post['id']);

    }


    protected function makePost()
    {
        $post = $this->make(Post::class);

        $categories = $this->create(Category::class, [], 2)->pluck('id')->toArray();

        return $post->toArray() + ['categories' => $categories];
    }

    protected function checkIfDatabaseHasMeta($key, $meta, $postId, $checkMissing = false)
    {
        $method = $checkMissing ? 'assertDatabaseMissing' : 'assertDatabaseHas';

        $this->$method('metas', [
            "key" => $key,
            "value" => $meta,
            "metaable_id" => $postId,
            "metaable_type" => "App\\Models\\Post"
        ]);
    }

    protected function checkIfDatabaseMissingMeta($key, $meta, $postId)
    {
        $this->checkIfDatabaseHasMeta($key, $meta, $postId, true);
    }


}
