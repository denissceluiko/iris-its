<?php

namespace Tests\Feature\Mattermost;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;
    /**
     * @test
     */
    public function denies_request_without_user_credentials()
    {
        $response = $this->noUser()->send('/team');

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function accepts_request_with_user_credentials()
    {
        $response = $this->send('/team');

        $response->assertSuccessful();
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

    /**
     * @test
     */
    public function finds_user_account_by_name_and_adds_mm_id()
    {
        $user = factory(User::class)->make();
        $mm_id = $user->mm_id;
        $user->mm_id = null;
        $user->save();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'mm_id' => null,
            'id' => $user->id,
        ]);

        $user->mm_id = $mm_id;

        $this->user($user)->send('/team');

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'mm_id' => $user->mm_id,
            'id' => $user->id,
        ]);
    }
}
