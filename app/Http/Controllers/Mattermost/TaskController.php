<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends MattermostController
{
    /**
     * Model of the team project is associated with.
     *
     * @var Team
     */
    protected $team;

    protected $aliases = [
        'add' => 'new',
        'create' => 'new',
        'mine' => 'my',
        'get' => 'take',
        'from' => 'list',
        'in' => 'list',
        'start' => 'status',
        'stop' => 'status',
        'done' => 'status',
    ];

    protected $defaultView = 'mattermost.task.help';


    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->team = Team::fromMattermost($request->team_id)->first();
    }

    public function optionNew()
    {
        if (count($this->args) < 2)
        {
            return $this->usage("`$this->command new project_code task_name` E.g. `$this->command new NYP Buy wine`.");
        }

        $code = mb_strtoupper($this->args[0]);
        $project = $this->team->projects()->withCode($code)->first();

        if (!$project)
        {
            return $this->response("Project with a code `$code` does not exist.");
        }

        $task = $project->newTask([
            'name' => implode(' ', array_slice($this->args, 1)),
            'assignee_id' => Auth::id(),
        ]);

        return $this->response("`$task->code` $task->name created.");

    }

    public function optionList()
    {
        if (!isset($this->args[0]))
        {
            return $this->usage("`$this->command list project_code` E.g. `$this->command list NYP`.");
        }

        $project = $this->team->projects()->withCode($this->args[0])->first();

        if (!$project)
        {
            return $this->response("Project {$this->args[0]} does not exist.");
        }

        return $this->response([
            'team' => $this->team,
            'project' => $project,
            'tasks' => $project->tasks()->with(['creator', 'assignee'])->get()
        ], 'mattermost.task.list');
    }

    public function optionMy()
    {
        $tasks = Auth::user()->tasks()->with('creator')->get();

        return $this->response([
            'tasks' => $tasks,
            'team' => $this->team,
        ], 'mattermost.task.my');
    }

    public function optionTake()
    {
        if (!isset($this->args[0]))
        {
            return $this->usage("`$this->command take task_code` E.g. `$this->command take NYP-42`.");
        }

        $code = $this->args[0];
        $task = $this->team->tasks()->withCode($code)->first();

        if (!$task)
        {
            return $this->response("Task `$code` does not exist");
        }

        $task->assignee_id = Auth::id();
        $task->update();

        return $this->response("`$task->code` is now assigned to you.");
    }

    public function optionAssign()
    {
        if (count($this->args) < 2)
        {
            return $this->usage("`$this->command assign task_code user_name` E.g. `$this->command assign NYP-42 john.smith`");
        }

        $code = $this->args[0];
        $task = $this->team->tasks()->withCode($code)->first();

        if (!$task)
        {
            return $this->response("Task `$code` does not exist.");
        }

        $user = User::withName(trim($this->args[1],'@'))->first();

        if (!$user)
        {
            $user = User::create(['name' =>$this->args[1]]);
        }

        $task->assign($user);

        return $this->response("`$code` is assigned to $user->name now.");

    }

    public function optionDrop()
    {
        if (!isset($this->args[0]))
        {
            return $this->usage("`$this->command drop task_code` E.g. `$this->command drop NYP-42`.");
        }

        $code = $this->args[0];
        $task = $this->team->tasks()->withCode($code)->first();

        if (!$task)
        {
            return $this->response("Task `$code` does not exist.");
        }

        if ($task->assignee_id != Auth::id())
        {
            return $this->response("Task `$code` is not assigned to you, you can't drop it.");
        }

        $task->drop();

        return $this->response("`$task->code` is now free from you :D");
    }

    /**
     * Changes status of a task.
     * Uses aliases to differentiate between actions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionStatus()
    {
        if (!isset($this->args[0]))
        {
            if ($this->option != 'status')
            {
                return $this->usage("`$this->command $this->option task_code` E.g. `$this->command $this->option NYP-42`.");
            }
            else
            {
                return $this->response('mattermost.task.status');
            }
        }

        $code = $this->args[0];
        $task = $this->team->tasks()->withCode($code)->first();

        if (!$task)
        {
            return $this->response("Task `$code` does not exist.");
        }

        switch ($this->option)
        {
            case 'start':
                $task->start();
                break;
            case 'stop':
                $task->stop();
                break;
            case 'done':
                $task->done();
                break;
        }

        return $this->response("`$task->code` is now $task->status.");
    }
}
