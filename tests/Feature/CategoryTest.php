<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function user_without_permissions_cannot_perform_crud()
    {
        $this->signInWithPermissions(['manage no taxonomy']);

        $category = $this->make(Category::class)->toArray();

        $this->post(route('admin.category.store'), [
            'name'        => 'category name',
            'description' => 'description'
        ])->assertStatus(403);

        $category = $this->create(Category::class, [
            'name'        => 'category name',
            'description' => 'description'
        ]);

        $this->patch(route('admin.category.update', $category->id), $category->toArray())->assertStatus(403);

        $this->delete(route('admin.category.destroy', $category->id))->assertStatus(403);
    }

    /** @test */
    public function a_category_can_be_created()
    {
        $this->signInWithPermissions(['manage taxonomy']);

        $category = $this->make(Category::class);

        $this->postJson(route('admin.category.store'), $category->toArray());

        $this->assertDatabaseHas('categories', $category->toArray());
    }

    /** @test */
    public function a_category_can_be_updated()
    {
        $this->signInAdmin();

        $laravel = $this->create(Category::class, ['name' => 'laravel']); // slug = 'laravel'

        $php = $this->create(Category::class, ['name' => 'php']);

        $this->patch(route('admin.category.update', $php->id), [
            'name'        => $laravel->name,
            'slug'        => $php->slug,
            'description' => 'blah blah'
        ])->assertSessionHasErrors('name');

        $this->patch(route('admin.category.update', $php->id), [
            'name'        => 'new name',
            'slug'        => $php->slug,
            'description' => 'blah blah'
        ])->assertSessionMissing('name');

        $this->assertEquals('new name', $php->fresh()->name);
    }

    /** @test */
    public function a_category_slug_is_editable_and_should_be_unique()
    {
        $this->signInAdmin();

        $laravel = $this->create(Category::class, ['name' => 'laravel']);

        $php = $this->create(Category::class, ['name' => 'php']);

        $this->patch(route('admin.category.update', $php->id), [
            'name'        => $php->name,
            'slug'        => $laravel->slug,
            'description' => 'blah blah'
        ])->assertSessionHasErrors('slug');

        $this->patch(route('admin.category.update', $php->id), [
            'name'        => $php->name,
            'slug'        => $php->slug,
            'description' => 'blah blah'
        ])->assertSessionMissing('slug');

        $this->patch(route('admin.category.update', $php->id), [
            'name'        => $php->name,
            'slug'        => 'random slug',
            'description' => 'blah blah'
        ])->assertSessionMissing('slug');
    }

    /** @test */
    public function a_category_with_child_categories_cannot_be_deleted()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $category = $this->create(Category::class);

        $child1 = $this->create(Category::class, ['parent_id' => $category->id]);

        $child2 = $this->create(Category::class, ['parent_id' => $category->id]);

        $this->delete(route('admin.category.destroy', $category->id));

        $this->assertDatabaseHas('categories', $category->toArray());

        $this->delete(route('admin.category.destroy', $child1->id));

        $this->assertDatabaseMissing('categories', $child1->toArray());
    }


}
