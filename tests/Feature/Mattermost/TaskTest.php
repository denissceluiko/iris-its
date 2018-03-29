<?php

namespace Tests\Feature\Mattermost;

use App\Project;
use App\Task;
use App\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;

    /**
     * @var Team
     */
    protected $team;

    /**
     * @var Project
     */
    protected $project;

    public function setUp()
    {
        parent::setUp();

        $this->team = factory(Team::class)->create();
        $this->project = $this->team->projects()->save(factory(Project::class)->make());
    }

    /**
     * @test
     */
    public function can_create_task()
    {
        $task = factory(Task::class)->make();
        $response = $this->text("new {$this->project->code} $task->name")->team($this->team)->send('/t');

        $response->assertSee("$task->name created");
    }


    /**
     * @test
     */
    public function increments_next_task_number()
    {
        $task = factory(Task::class)->make();
        $this->project = $this->project->fresh();
        $this->text("new {$this->project->code} $task->name")->team($this->team)->send('/t');

        $this->assertDatabaseHas('projects',[
            'next_task_number' => $this->project->next_task_number+1,
        ]);
    }

    /**
     * @test
     */
    public function must_have_arguments()
    {
        $response = $this->text("new")->team($this->team)->send('/t');

        $response->assertSee("Usage");
    }

    /**
     * @test
     */
    public function rejects_not_existing_projects()
    {
        $task = factory(Task::class)->make();
        $code = substr(md5($this->project->code), 0, 4);

        $response = $this->text("new $code $task->name")->team($this->team)->send('/t');

        $response->assertJsonFragment(['text' => "Project with a code `".mb_strtoupper($code)."` does not exist."]);
    }

    /**
     * @test
     */
    public function project_code_is_automatically_capitalized()
    {
        $task = factory(Task::class)->make();
        $code = mb_strtolower($this->project->code);
        $response = $this->text("new $code $task->name")->team($this->team)->send('/t');

        $response->assertSee("$task->name created");
    }
}
