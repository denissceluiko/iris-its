<?php

namespace Tests\Feature\Mattermost;

use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;

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
    public function can_create_task()
    {
        $task = factory(Task::class)->make();
        $response = $this->text("new {$this->project->code} $task->name")->team($this->team)->send('/t');

        $response->assertSuccessful();
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

        $response->assertSuccessful();
        $response->assertSee("Usage");
    }

    /**
     * @test
     */
    public function rejects_creating_tasks_in_not_existing_projects()
    {
        $task = factory(Task::class)->make();
        $code = substr(md5($this->project->code), 0, 4);

        $response = $this->text("new $code $task->name")->team($this->team)->send('/t');

        $response->assertSuccessful();
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

        $response->assertSuccessful();
        $response->assertSee($this->project->code);
    }

    /**
     * @test
     */
    public function can_see_task_list()
    {
        $this->actingAs($this->user);

        $tasks = factory(Task::class, 5)->make();
        $tasks->each(function($task){
            $this->project->newTask(['name' => $task->name]);
        });

        $response = $this->text("list {$this->project->code}")->team($this->team)->send('/t');

        $response->assertSuccessful();

        foreach($tasks as $task)
        {
            $response->assertSee($task->name);
        }
    }

    /**
     * @test
     */
    public function user_can_see_their_task_list()
    {
        $this->actingAs($this->user);

        $project = $this->project;

        $tasks = factory(Task::class, 5)->make(['assignee_id' => $this->user->id]);
        $tasks->each(function($task) use ($project) {
            $project->newTask($task);
        });

        $response = $this->text("my")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();

        foreach($tasks as $task)
        {
            $response->assertSee($task->name);
        }
    }

    /**
     * @test
     */
    public function task_list_must_have_project_code_provided()
    {
        $response = $this->text('list')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee('Usage');
    }

    /**
     * @test
     */
    public function cannot_list_tasks_of_a_project_that_does_not_exist()
    {
        $response = $this->text('list KEKMEISTARS')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee('Project KEKMEISTARS does not exist.');
    }

    /**
     * @test
     */
    public function can_take_task()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make();
        $task = $this->project->newTask($task);

        $response = $this->text("take $task->code")->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is now assigned to you.");
    }

    /**
     * @test
     */
    public function must_provide_task_code_to_take_task()
    {
        $response = $this->text('take')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee('Usage');
        $response->assertSee('code');
    }

    /**
     * @test
     */
    public function cant_take_task_that_does_not_exist()
    {
        $response = $this->text('take KEKMEISTARS-6942')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee("does not exist");
    }

    /**
     * @test
     */
    public function can_drop_task()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make(['assignee_id' => $this->user->id]);
        $task = $this->project->newTask($task);

        $response = $this->text("drop $task->code")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is now free from you");
    }

    /**
     * @test
     */
    public function cannot_drop_task_that_is_not_yours()
    {
        $this->actingAs($this->user);
        $task = factory(Task::class)->make(['assignee_id' => $this->user->id]);
        $task = $this->project->newTask($task);

        $response = $this->text("drop $task->code")->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is not assigned to you, you can't drop it");
    }

    /**
     * @test
     */
    public function must_provide_task_code_to_drop_task()
    {
        $response = $this->text('drop')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee('Usage');
        $response->assertSee('code');
    }

    /**
     * @test
     */
    public function cannot_drop_task_that_does_not_exist()
    {
        $response = $this->text('drop KEKMEISTARS-6942')->team()->send('/t');

        $response->assertSuccessful();
        $response->assertSee("does not exist");
    }

    /**
     * @test
     */
    public function can_assign_task()
    {
        $this->actingAs($this->user);
        $assignee = factory(User::class)->create();
        $task = factory(Task::class)->make();
        $task = $this->project->newTask($task);

        $response = $this->text("assign $task->code $assignee->name")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is assigned to $assignee->name now.");
    }

    /**
     * @test
     */
    public function must_have_arguments_to_assign_task()
    {
        $response = $this->text("assign")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("Usage");
    }

    /**
     * @test
     */
    public function must_have_two_arguments_to_assign_task()
    {
        $response = $this->text("assign KEKMEISTARS-42")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("Usage");
    }

    /**
     * @test
     */
    public function task_must_exist_before_being_assigned()
    {
        $response = $this->text("assign KEKMEISTARS-42 $this->user->name")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`KEKMEISTARS-42` does not exist");
    }

    /**
     * @test
     */
    public function can_assign_to_user_that_does_not_exist()
    {
        $this->actingAs($this->user);
        $assignee = factory(User::class)->make();
        $task = factory(Task::class)->make();
        $task = $this->project->newTask($task);

        $response = $this->text("assign $task->code $assignee->name")->user($this->user)->team($this->team)->send('/t');

        $response->assertSuccessful();
        $response->assertSee("`$task->code` is assigned to $assignee->name now.");
        $this->assertDatabaseHas('users', [
            'name' => $assignee->name
        ]);
    }
}
