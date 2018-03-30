<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    private $team;
    private $user;
    private $project;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->team = factory(Team::class)->create();
        $this->project = factory(Project::class)->create(['team_id' => $this->team->id]);

        $this->actingAs($this->user);
    }

    /**
     * @test
     */
    public function task_can_see_project()
    {
        $task = $this->project->newTask(factory(Task::class)->make());

        $this->assertEquals($this->project->id, $task->project->id);
    }

    /**
     * @test
     */
    public function task_can_see_creator()
    {
        $task = $this->project->newTask(factory(Task::class)->make());

        $this->assertEquals($this->user->id, $task->creator_id);
    }

    /**
     * @test
     */
    public function task_can_see_assignee()
    {
        $assignee = factory(User::class)->create();
        $task = $this->project->newTask(factory(Task::class)->make(['assignee_id' => $assignee->id]));

        $this->assertEquals($assignee->id, $task->assignee_id);
    }

    /**
     * @test
     */
    public function task_can_be_dropped()
    {
        $task = $this->project->newTask(factory(Task::class)->make(['assignee_id' => $this->user->id]));

        $this->assertEquals($this->user->id, $task->assignee_id);

        $task->drop();

        $this->assertEquals(null, $task->assignee_id);
    }

    /**
     * @test
     */
    public function task_can_be_found_by_code()
    {
        $task = $this->project->newTask(factory(Task::class)->make());

        $found = Task::withCode($task->code)->first();

        $this->assertEquals($task->code, $found->code);
    }
}
