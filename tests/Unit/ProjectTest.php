<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    private $team;
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->team = factory(Team::class)->create();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function can_create_task()
    {
        $this->actingAs($this->user);

        $project = $this->team->projects()->save(factory(Project::class)->make());

        $task = factory(Task::class)->make();

        $created = $project->newTask([
            'name' => $task->name
        ]);

        $this->assertEquals($task->name, $created->name);
        $this->assertDatabaseHas('tasks', [
            'name' => $task->name,
            'code' => $project->code.'-1',
            'creator_id' => $this->user->id,
        ]);
    }

    /**
     * @test
     */
    public function cannot_create_task_without_name()
    {
        $project = $this->team->projects()->save(factory(Project::class)->make());

        $created = $project->newTask([]);

        $this->assertEquals(null, $created);
    }

    /**
     * @test
     */
    public function can_get_team_through_project()
    {
        $project = $this->team->projects()->save(factory(Project::class)->make());

        $this->assertEquals($this->team->mm_domain, $project->team->mm_domain);
    }

    /**
     * @test
     */
    public function project_can_be_found_by_code()
    {
        $project = $this->team->projects()->save(factory(Project::class)->make());

        $found = $this->team->projects()->withCode($project->code)->first();

        $this->assertEquals($project->code, $found->code);
    }


    /**
     * @test
     */
    public function project_can_be_found_by_name()
    {
        $project = $this->team->projects()->save(factory(Project::class)->make());

        $found = $this->team->projects()->withName($project->name)->first();

        $this->assertEquals($project->name, $found->name);
    }
}
