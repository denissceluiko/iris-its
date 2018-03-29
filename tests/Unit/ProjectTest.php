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

    /**
     * @test
     */
    public function can_create_task()
    {
        $this->actingAs(factory(User::class)->create());

        $team = factory(Team::class)->create();
        $project = $team->projects()->save(factory(Project::class)->make());

        $this->actingAs(factory(User::class)->create());

        $task = factory(Task::class)->make();

        $created = $project->newTask([
            'name' => $task->name
        ]);

        $this->assertEquals($task->name, $created->name);
    }
}
