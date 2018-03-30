<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends MattermostController
{
    /**
     * @var Team
     */
    protected $team;

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
        return $this->response();
    }
}
