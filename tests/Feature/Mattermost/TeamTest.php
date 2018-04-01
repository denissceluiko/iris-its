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
        $mm_id = $team->mm_id;
        $team->mm_id = null;
        $team->save();
        $team->mm_id = $mm_id;

        $response = $this->text('init')->team($team)->send('/team');

        $response->assertSuccessful();
        $response->assertSee("`$team->mm_domain` initialization has been finalized");
    }

    /**
     * @test
     */
    public function will_not_init_if_exists()
    {
        $team = factory(Team::class)->create();

        $response = $this->text('init')->team($team)->send('/team');

        $response->assertSuccessful();
        $response->assertSee("`$team->mm_domain` is already initialized.");
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
