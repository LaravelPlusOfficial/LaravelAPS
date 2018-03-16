<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmEmailTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function email_token_created_on_new_user_registered()
    {
        $this->withoutExceptionHandling();

        $user = $this->create(User::class);

        $this->assertNotNull($user->email_verification_token, "Email confirmation token is not generated");
    }

}
