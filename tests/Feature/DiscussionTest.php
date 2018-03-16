<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Rules\Recaptcha;
use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscussionTest extends TestCase
{

    use RefreshDatabase;

    protected $comment;

    public function setUp()
    {
        parent::setUp();

        $this->comment = factory(Comment::class)->create();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function unauthenticated_user_cannot_participate_in_discussion()
    {
        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post(route('comment.store'), []);
    }

    /** @test */
    public function an_authenticated_user_can_post_comment()
    {
        $this->withoutExceptionHandling();

        $article = $this->create(Post::class);

        $this->signInConfirmed();

        $johnComment = $this->make(Comment::class, ['post_id' => $article->id]);

        $this->post(
            route('comment.store', $article->id),
            array_merge($johnComment->toArray(), ['gRecaptchaResponse' => 'token'])
        );

        $this->assertEquals($johnComment->body, $article->comments->first()->toArray()['body']);
    }

    /** @test */
    public function an_authenticated_user_can_reply_a_comment()
    {
        $this->withoutExceptionHandling();

        $this->signIn($jane = $this->create(User::class, ['email_verified' => true]));

        $reply = $this->make(Comment::class, [
            'post_id'   => $this->comment->post_id,
            'parent_id' => $this->comment->id
        ]);

        $this->post(route('reply.store'), array_merge($reply->toArray(), ['gRecaptchaResponse' => 'token']));

        $this->assertEquals($reply->body, $this->comment->replies()->first()->body);
    }

    /** @test */
    public function a_comment_require_body()
    {
        $this->publishComment(['body' => ''])
            ->assertSessionHasErrors('body');

    }

    /** @test */
    public function a_comment_require_valid_post_id()
    {
        $this->publishComment(['post_id' => ''])
            ->assertSessionHasErrors('post_id');

        $this->publishComment(['post_id' => 99999])
            ->assertSessionHasErrors('post_id');

        $post = $this->create(Post::class);

        $this->publishComment(['post_id' => $post->id])
            ->assertSessionMissing('post_id');
    }

    /** @test */
    public function a_reply_require_a_body()
    {
        $this->publishReply(['body' => ''])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_reply_require_a_valid_parent_id()
    {
        $this->publishReply(['parent_id' => ''])
            ->assertSessionHasErrors('parent_id');

        $this->publishReply(['parent_id' => 9999])
            ->assertSessionHasErrors('parent_id');

        $this->publishReply()
            ->assertSessionMissing('parent_id');
    }

    /** @test */
    public function only_authorized_user_can_update_comment()
    {
        // Only Authorized users can update their comments
        $jenny = $this->signInConfirmed();

        $jennyComment = $this->create(Comment::class, ['user_id' => $jenny->id]);

        $this->patch(route('comment.update', $jennyComment->id), [
            'body'               => 'new body',
            'gRecaptchaResponse' => 'token'
        ]);

        tap($jennyComment->fresh(), function ($jennyComment) {
            $this->assertEquals('new body', $jennyComment->body);
        });

        // Unauthorized users should not able to update somebody's else comments
        $this->signInConfirmed();

        $this->patch(route('comment.update', $jennyComment->id), [
            'body'               => 'Gibrish',
            'gRecaptchaResponse' => 'token'
        ])->assertStatus(403);
    }

    /** @test */
    public function only_authorized_user_can_delete_comment()
    {
        // Authorized person should able to delete own thread
        $jenny = $this->signInConfirmed();

        $jennyComment = $this->create(Comment::class, ['user_id' => $jenny->id]);

        $jennyCommentArray = $jennyComment->toArray();

        $this->delete(route('comment.destroy', $jennyComment->id), [
            'id'                 => $jennyComment->id,
            'gRecaptchaResponse' => 'token'
        ]);

        $this->assertDatabaseMissing('comments', $jennyCommentArray);

        // Unauthorized person should not able to delete somebody's else thread
        $this->withoutExceptionHandling();

        $this->expectException("\Illuminate\Auth\Access\AuthorizationException");

        $this->signInConfirmed();

        $this->delete(route('comment.destroy', $this->comment->id), [
            'id'                 => $this->comment->id,
            'gRecaptchaResponse' => 'token'
        ])->assertStatus(403);
    }

    /** @test */
    public function on_deleting_a_comment_all_replies_should_be_remove()
    {
        $this->withoutExceptionHandling();

        $john = $this->create(User::class, ['email_verified' => true]);

        $rootComment = $this->create(Comment::class, ['user_id' => $john->id, 'body' => 'root']);
        $level1Comment = $this->create(Comment::class, ['parent_id' => $rootComment->id, 'body' => 'Level 1']);
        $level2Comment = $this->create(Comment::class, ['parent_id' => $level1Comment->id, 'body' => 'Level 2']);
        $level3Comment = $this->create(Comment::class, ['parent_id' => $level2Comment->id, 'body' => 'Level 3']);

        $rootCommentArray = $rootComment->toArray();
        $level1CommentArray = $level1Comment->toArray();
        $level2CommentArray = $level2Comment->toArray();
        $level3CommentArray = $level3Comment->toArray();

        $this->signIn($john);
        $this->delete(route('comment.destroy', $rootComment->id), [
            'gRecaptchaResponse' => 'token'
        ]);

        $this->assertDatabaseMissing('comments', $rootCommentArray);
        $this->assertDatabaseMissing('comments', $level1CommentArray);
        $this->assertDatabaseMissing('comments', $level2CommentArray);
        $this->assertDatabaseMissing('comments', $level3CommentArray);

    }

    /** @test */
    public function a_user_should_not_reply_to_his_own_comment()
    {
        $this->signIn($john = $this->create(User::class, ['email_verified' => true]));

        $johnComment = $this->create(Comment::class, ['user_id' => $john->id]);

        $johnReply = $this->make(Comment::class, ['parent_id' => $johnComment->id]);

        $this->post(route('reply.store'), array_merge($johnReply->toArray(), ['gRecaptchaResponse' => 'token']))
            ->assertStatus(403);
    }

    /** @test */
    public function comment_body_should_be_sanitize_before_displaying_to_frontend()
    {
        $this->withoutExceptionHandling();

        $body = '<script>alert("alert")</script>';
        $body .= '<p>paragraph</p><h1>H1</h1><h2>H2</h2>';
        $body .= '<pre><code><script>alert("alert")</script></code></pre>';

        $response = $this->publishComment(['body' => $body]);

        $comment = json_decode($response->getContent(), true);

        $comment = Comment::whereId($comment['id'])->firstOrFail();

        $expectation = '<p>paragraph</p><h1>H1</h1><h2>H2</h2>';
        $expectation .= '<pre><code></code></pre>';

        $this->assertEquals($expectation, $comment->body);
    }

    /** @test */
    public function comment_require_to_validate_recaptcha()
    {
        unset(app()[Recaptcha::class]);

        $this->publishComment(['gRecaptchaResponse' => null])
            ->assertSessionHasErrors('gRecaptchaResponse');
    }


    protected function publishComment($overrides = [])
    {
        $this->signIn($john = $this->create(User::class, ['email_verified' => true]));

        $overrides = array_merge([
            'gRecaptchaResponse' => 'token'
        ], $overrides);

        $comment = $this->make(Comment::class, $overrides);

        return $this->post(route('comment.store'), $comment->toArray());
    }

    protected function publishReply($overrides = [])
    {
        $this->signIn($john = $this->create(User::class, ['email_verified' => true]));

        $data = array_merge([
            'parent_id' => $this->comment->id,
            'post_id'   => $this->comment->post_id
        ], $overrides);

        $reply = $this->make(Comment::class, $data);

        return $this->post(route('reply.store'), $reply->toArray());
    }


}
