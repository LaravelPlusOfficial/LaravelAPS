<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_without_permissions_cannot_update_or_delete()
    {
        $this->signInWithPermissions(['manage no taxnomoy']);

        $tag = $this->create(Tag::class);

        $this->patch(route('admin.tag.update', $tag->id), $tag->toArray())->assertStatus(403);

        $this->delete(route('admin.tag.destroy', $tag->id))->assertStatus(403);
    }

    /** @test */
    public function a_tag_can_be_created()
    {
        $this->withoutExceptionHandling();

        $this->signInWithPermissions(['manage taxonomy']);

        $tag = $this->make(Tag::class);

        $this->post(route('admin.tag.store'), $tag->toArray());

        $this->assertDatabaseHas('tags', $tag->toArray());
    }

    /** @test */
    public function a_tag_can_be_updated()
    {
        $this->signInAdmin();

        $laravel = $this->create(Tag::class, ['name' => 'laravel']); // slug = 'laravel'

        $php = $this->create(Tag::class, ['name' => 'php']);

        $this->patch(route('admin.tag.update', $php->id), [
            'name' => $laravel->name
        ])->assertSessionHasErrors('name');

        $this->patch(route('admin.tag.update', $php->id), [
            'name' => 'new name',
            'slug' => $php->slug
        ])->assertSessionMissing('name');

        $this->assertEquals('new name', $php->fresh()->name);
    }

    /** @test */
    public function a_tag_slug_is_editable_and_should_be_unique()
    {
        $this->signInAdmin();

        $laravel = $this->create(Tag::class, ['name' => 'laravel']);

        $php = $this->create(Tag::class, ['name' => 'php']);

        $this->patch(route('admin.tag.update', $php->id), [
            'name' => $php->name,
            'slug' => $laravel->slug
        ])->assertSessionHasErrors('slug');

        $this->patch(route('admin.tag.update', $php->id), [
            'name' => $php->name,
            'slug' => $php->slug
        ])->assertSessionMissing('slug');

        $this->patch(route('admin.tag.update', $php->id), [
            'name' => $php->name,
            'slug' => 'random slug'
        ])->assertSessionMissing('slug');
    }

    /** @test */
    public function a_tag_should_be_able_to_deleted()
    {
        $this->withoutExceptionHandling();

        $this->signInWithPermissions(['manage taxonomy']);

        $tag = $this->create(Tag::class);

        $this->delete(route('admin.tag.destroy', $tag->id));

        $this->assertDatabaseMissing('tags', $tag->toArray());
    }


}
