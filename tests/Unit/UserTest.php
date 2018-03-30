<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function can_get_users_tasks()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $team = factory(Team::class)->create();
        $project = factory(Project::class)->create(['team_id' => $team]);

        $tasks = factory(Task::class, 5)
            ->make(['project_id' => $project->id, 'assignee_id' => $user->id])
            ->each(function ($task) use ($project) {
                $project->newTask($task);
            });

        foreach ($tasks as $task)
        {
            $this->assertDatabaseHas('tasks', [
                'project_id' => $project->id,
                'assignee_id' => $user->id,
                'name' => $task->name,
            ]);
        }

    }

    /**
     * @test
     */
    public function can_get_user_by_mm_id()
    {
        $user = factory(User::class)->create();

        $res = User::fromMattermost($user->mm_id)->first();

        $this->assertEquals($user->mm_id, $res->mm_id);
    }
}
