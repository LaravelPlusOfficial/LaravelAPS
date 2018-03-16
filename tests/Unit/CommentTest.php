<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{

    use RefreshDatabase;

    protected $comment;

    public function setUp()
    {
        parent::setUp();

        $this->comment = factory(Comment::class)->create();
    }

    /** @test */
    public function it_has_an_owner()
    {
        $this->assertInstanceOf(User::class, $this->comment->owner);
    }

    /** @test */
    public function it_has_replies()
    {
        $reply = $this->create(Comment::class, ['parent_id' => $this->comment->id, 'body' => 'reply']);

        $firstReply = Comment::where('id', $this->comment->id)->with('replies')->firstOrFail()->replies->first();

        $this->assertInstanceOf(Comment::class, $firstReply);

        $this->assertEquals($reply->body , $firstReply->body);
    }
    
//    /** @test */
//    public function it_parse_markdown_to_html()
//    {
//        $comment = $this->create(Comment::class, ['body' => 'foo']);
//
//        $this->assertEquals('<p>foo</p>', $comment->body);
//    }

}
