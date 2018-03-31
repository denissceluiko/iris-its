<?php

namespace Tests\Feature\Mattermost;

use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use MattermostRequest;
    use DatabaseTransactions;

    /**
     * @var Team
     */
    protected $team;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->team = factory(Team::class)->create();
        $this->project = $this->team->projects()->save(factory(Project::class)->make());
    }

    /**
     * @test
     */
    public function can_start_task()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make(['assignee_id' => $this->user->id]);
        $task = $this->project->newTask($task);

        $response = $this->text("start $task->code")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is now in progress");
    }

    /**
     * @test
     */
    public function can_stop_task()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make(['assignee_id' => $this->user->id]);
        $task = $this->project->newTask($task);

        $response = $this->text("stop $task->code")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is now on hold");
    }

    /**
     * @test
     */
    public function can_finish_task()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make(['assignee_id' => $this->user->id]);
        $task = $this->project->newTask($task);

        $response = $this->text("done $task->code")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is now done");
    }

    /**
     * @test
     */
    public function can_get_status_description_list()
    {
        $response = $this->text("status")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("List of available actions to change task's status");
    }

    /**
     * @test
     */
    public function must_provide_task_code_to_change_status()
    {
        $response = $this->text("done")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee('Usage');
        $response->assertSee('task_code');
    }

    /**
     * @test
     */
    public function task_must_exist_to_change_status()
    {
        $response = $this->text("done KEKMEISTARS-42")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee('does not exist');
    }
}
