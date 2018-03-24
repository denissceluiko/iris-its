<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;
use Illuminate\Http\Request;

class ProjectController extends MattermostController
{
    /**
     * Model of the team project is associated with.
     *
     * @var Team
     */
    protected $team;

    protected $helpMessage = <<<EOT
Use `/p` with following options:

| Command | Usage             | Example |
| :------ | :---------------- | :------ |
| help    | Show this message | /p help
| new     | project_code project_name | /p new NYP New Year's party |

For example `/p help` displays this message.
EOT;

    /**
     * ProjectController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->team = Team::where('mm_id', $request->team_id)->first();
    }

    /**
     * Create a project
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionNew()
    {
        if (count($this->args) < 2)
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => 'Usage: `/p new project_code project_name` E.g. `/p new NYP New year\'s party`.',
            ]);
        }
        $code = $this->args[0];
        $name = $this->args[1];

        $project = $this->team->projects()->where('name', $name)->first();
        if ($project)
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => "Project named $name already exists in this team.",
            ]);
        }

        $project = $this->team->projects()->where('code', $code)->first();
        if ($project)
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => "Project with a code `$code` already exists in this team.",
            ]);
        }

        $this->team->projects()->create(compact('name', 'code'));

        return response()->json([
            'response_type' => 'ephemeral',
            'text' => "Project $name created! Use `/t new $code` to add a new task.",
        ]);
    }
}
