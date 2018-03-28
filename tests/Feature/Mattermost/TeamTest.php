<?php

namespace Tests\Feature\Mattermost;

use App\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;

    /**
     * @test
     * @return void
     */
    public function can_be_inited()
    {
        $team = factory(Team::class)->make();

        $response = $this->text('init')->team($team)->send('/team');

        $response->assertJsonFragment(['text' => "Team `$team->mm_domain` has been initialized."]);
    }

    /**
     * @test
     */
    public function will_not_init_if_exists()
    {
        $team = factory(Team::class)->create();

        $response = $this->text('init')->team($team)->send('/team');

        $response->assertJsonFragment(['text' => "Team `$team->mm_domain` already exists."]);
    }

    /**
     * @test
     */
    public function help_is_available()
    {
        $response = $this->text('help')->send('/team');

        $response->assertSuccessful();
        $response->assertSee('/team help');
    }

    /**
     * @test
     */
    public function help_if_no_arguments_provided()
    {
        $response = $this->send('/team');

        $response->assertSuccessful();
        $response->assertSee('/team help');
    }

    /**
     * @test
     */
    public function test_dump()
    {
        $response = $this->text('dump')->send('/team');

        $response->assertSuccessful();
        $response->assertSee('Requested params');
    }
}
