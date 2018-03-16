<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterTest extends TestCase
{

    use RefreshDatabase;

    protected $validEmail;

    public function setUp()
    {
        parent::setUp();

        $this->validEmail = 'email@example.com';
    }

    /** @test */
    public function user_can_subscribe_to_newsletter()
    {
        $this->post(route('newsletter.subscribe'), ['email' => $this->validEmail]);

        $this->assertDatabaseHas('subscribers', ['email' => $this->validEmail]);
    }

    /** @test */
    public function user_can_unsubscribe_to_newsletter()
    {
        $this->post(route('newsletter.subscribe'), ['email' => $this->validEmail]);

        $this->delete(route('newsletter.unsubscribe', $this->validEmail));

        $this->assertDatabaseMissing('subscribers', ['email' => $this->validEmail]);
    }
    
    /** @test */
    public function user_email_should_be_valid()
    {
        $this->post(route('newsletter.subscribe'), [])
            ->assertSessionHasErrors('email');

        $this->post(route('newsletter.subscribe'), ['email' => 'emailNotValid'])
            ->assertSessionHasErrors('email');
    }
    
    /** @test */
    public function user_email_should_be_unique()
    {
        $this->post(route('newsletter.subscribe'), ['email' => $this->validEmail]);

        $this->post(route('newsletter.subscribe'), ['email' => $this->validEmail])
            ->assertSessionHasErrors('email');

    }

    
}
