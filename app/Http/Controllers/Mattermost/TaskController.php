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

    protected $helpView = 'mattermost.task.help';


    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->team = Team::where('mm_id', $request->team_id)->first();
    }

    public function optionNew()
    {
        if (count($this->args) < 2)
        {
            return $this->response('Usage: `/t new project_code task_name` E.g. `/t new NYP Buy wine`.');
        }

        $code = $this->args[0];
        $project = $this->team->projects()->where('code', $code)->first();

        if (!$project)
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => "Project with a code `$code` does not exist.",
            ]);
        }

        $task = $project->tasks()->create([
            'name' => implode(' ', array_slice($this->args, 1)),
            'code' => "{$code}-{$project->next_task_number}",
            'creator_id' => Auth::user()->id,
            'assignee_id' => Auth::user()->id,
        ]);

        $project->increment('next_task_number');

        return response()->json([
            'response_type' => 'ephemeral',
            'text' => "`$task->code` $task->name created.",
        ]);

    }

    public function optionAll()
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }

    public function optionMy()
    {
        $tasks = Auth::user()->tasks;
        $res_text = <<<EOT
        | Code | Name |
        | :--- | :--- |
EOT;

        foreach ($tasks as $task)
        {
            $res_text .= "| $task->code | $task->name |
            ";
        }
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }

    public function optionTake()
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }
}
