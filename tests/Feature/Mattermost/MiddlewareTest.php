<?php

namespace Tests\Feature\Mattermost;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;
    /**
     * @test
     */
    public function denies_request_without_user_credentials()
    {
        $response = $this->send('/team');

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function accepts_request_with_user_credentials()
    {
        $response = $this->user()->send('/team');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function creates_user_account_if_it_does_not_exist()
    {
        $user = factory(User::class)->make();

        $this->user($user)->send('/team');

        $this->assertDatabaseHas('users', [
            'mm_id' => $user->mm_id
        ]);
    }
}
