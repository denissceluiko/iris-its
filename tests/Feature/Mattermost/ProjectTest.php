<?php

namespace Tests\Feature\Mattermost;

use App\Project;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;

    protected $user;
    protected $team;
    protected $team2;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->team = factory(Team::class)->create(['mm_id' => 'team1id', 'mm_domain' => 'Team 1']);
        $this->team2 = factory(Team::class)->create(['mm_id' => 'team2id', 'mm_domain' => 'Team 2']);
    }

    /**
     * @test
     */
    public function can_create_project()
    {
        $project = factory(Project::class)->make();

        $response = $this->text("new $project->code $project->name")->team($this->team)->user($this->user)->send('/pr');

        $this->assertDatabaseHas('projects', [
            'name' => $project->name,
            'code' => $project->code,
        ]);
    }

    /**
     * @test
     */
    public function if_not_enough_arguments_a_usage_example_is_shown()
    {
        $response = $this->text('new kek')->team($this->team)->user($this->user)->send('/pr');
        $response->assertSee('Usage')->assertSee('/pr');
    }

    /**
     * @test
     */
    public function cannot_create_a_project_with_the_same_name_in_the_team()
    {
        $project = factory(Project::class)->make();
        $this->team->projects()->save($project);

        $response = $this->text("new $project->code $project->name")->team($this->team)->user($this->user)->send('/pr');
        $response->assertSee("Project named $project->name already exists in {$this->team->mm_domain}.");
    }

    /**
     * @test
     */
    public function cannot_create_a_project_with_the_same_code_in_the_team()
    {
        $project = factory(Project::class)->make();
        $this->team->projects()->save($project);

        $response = $this->text("new $project->code ".substr(md5($project->name), 0, 4))->team($this->team)->user($this->user)->send('/pr');
        $response->assertSee("Project with a code `$project->code` already exists in {$this->team->mm_domain}.");
    }

    /**
     * @test
     */
    public function can_create_a_project_with_identical_names_in_different_teams()
    {
        $project = factory(Project::class)->make();
        $this->team->projects()->save($project);

        $response = $this->text("new $project->code $project->name")->team($this->team2)->user($this->user)->send('/pr');
        $this->assertDatabaseHas('projects', [
            'name' => $project->name,
            'code' => $project->code,
            'team_id' => $this->team2->id
        ]);
    }

    /**
     * @test
     */
    public function can_create_a_project_with_identical_codes_in_different_teams()
    {
        $project = factory(Project::class)->make();
        $this->team->projects()->save($project);

        $response = $this->text("new $project->code ".md5($project->name))->team($this->team2)->user($this->user)->send('/pr');
        $this->assertDatabaseHas('projects', [
            'name' => md5($project->name),
            'code' => $project->code,
            'team_id' => $this->team2->id
        ]);
    }

    /**
     * @test
     */
    public function will_capitalize_project_code()
    {
        $project = factory(Project::class)->make(['code' => 'low']);
        $response = $this->text("new $project->code $project->name")->team($this->team)->user($this->user)->send('/pr');

        $response->assertJsonFragment(['text' => "Project $project->name created! Use `/t new ".mb_strtoupper($project->code)."` to add a new task."]);
        $this->assertDatabaseHas('projects', [
            'name' => $project->name,
            'code' => mb_strtoupper($project->code),
            'team_id' => $this->team->id
        ]);

    }

    /**
     * @test
     */
    public function help_is_available()
    {
        $response = $this->text('help')->team()->send('/pr');

        $response->assertSuccessful();
        $response->assertSee('/pr help');
    }

    /**
     * @test
     */
    public function help_if_no_arguments_provided()
    {
        $response = $this->team()->send('/pr');

        $response->assertSuccessful();
        $response->assertSee('/pr help');
    }

}
