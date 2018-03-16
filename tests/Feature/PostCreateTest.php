<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCreateTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_may_not_create_post()
    {
        $this->withExceptionHandling();

        $this->get(route('admin.post.create'))
            ->assertRedirect(route('login'));

        $this->post(route('admin.post.store'))
            ->assertRedirect(route('login'));

    }

    /** @test */
    public function a_post_is_publishable()
    {
        $this->withoutExceptionHandling();

        $this->signInConfirmed();

        $categories = $this->create(Category::class, [], 3)->pluck('id');

        $post = $this->make(Post::class, [ 'title' => 'post title', 'categories' => $categories->toArray()]);

        $response = $this->post(route('admin.post.store'), $post->toArray());

        $databasePost = Post::whereId($this->getPostFromResponse($response)['id'])->firstOrFail();

        $this->assertEquals('post title', $databasePost->title);
    }

    /** @test */
    public function new_users_must_first_confirm_their_email_address_before_creating_post()
    {
        $user = $this->create(User::class);

        $this->signIn($user);

        $this->post(route('admin.post.store'), [])
            ->assertRedirect(route('email.confirmation.show'));
    }

    /** @test */
    public function a_post_is_updateable()
    {
        $this->signInConfirmed();

        $categories = $this->create(Category::class, [], 3)->pluck('id');

        $post = $this->create(Post::class);

        $data = array_merge($post->toArray(), [
            'title' => 'new title',
            'categories' => $categories->toArray()
        ]);

        $this->patch(route('admin.post.update', $post->id), $data);

        $this->assertEquals('new title', $post->fresh()->title);
    }

    /** @test */
    public function a_post_slug_is_updateable_and_should_be_unique()
    {
        $this->signInConfirmed();

        $categories = $this->create(Category::class, [], 3)->pluck('id')->toArray();

        $postOne = $this->create(Post::class, ['title' => 'title one']); // slug => title-one

        $postTwo = $this->create(Post::class, ['title' => 'title two']);

        $data = array_merge($postTwo->toArray(), ['slug' => null, 'categories' => $categories]);

        $this->patch(route('admin.post.update', $postTwo->id), $data)
            ->assertSessionHasErrors('slug');

        $data = array_merge($postTwo->toArray(), ['slug' => 'title-one', 'categories' => $categories]);

        $this->patch(route('admin.post.update', $postTwo->id), $data)
            ->assertSessionHasErrors('slug');

        $data = array_merge($postTwo->toArray(), ['slug' => 'title-three', 'categories' => $categories]);

        $this->patch(route('admin.post.update', $postTwo->id), $data)
            ->assertSessionMissing('slug');

        $this->assertEquals($postTwo->fresh()->slug, 'title-three');
    }

    /** @test */
    public function a_post_can_be_deleted()
    {
        $this->signInConfirmed();

        $post = $this->create(Post::class);

        $this->delete(route('admin.post.destroy', $post->id));

        $this->assertDatabaseMissing('posts', $post->toArray());
    }


    /** @test */
    public function a_post_require_a_title()
    {
        $this->publishPost(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_post_require_a_body()
    {
        $this->publishPost(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_post_require_valid_category()
    {
        $this->signInConfirmed();

        $this->create(Category::class, [], 3);

        $post = $this->make(Post::class, ['category_slug' => 'abc_random']);

        $response = $this->post(route('admin.post.store'), $post->toArray());

        $response->assertSessionHasErrors('category_slug');

    }

    /** @test */
    public function a_post_require_a_slug()
    {
        $post = $this->create(Post::class, ['title' => 'post title']);

        $this->assertEquals($post->slug, 'post-title');

    }

    protected function publishPost($overrides = [])
    {
        $this->signInConfirmed();

        $post = $this->make(Post::class, $overrides);

        return $this->post(route('admin.post.store'), $post->toArray());
    }

    /**
     * @param $response
     * @return array
     */
    public function getPostFromResponse($response): array
    {
        return json_decode($response->getContent(), 1);
    }


}
