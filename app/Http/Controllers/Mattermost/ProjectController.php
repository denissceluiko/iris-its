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
| create  | [project_name] [project code] | /p create "Dzimšanas diena 2018" DZD |

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
    public function optionCreate()
    {
        if (count($this->args) < 2)
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => 'Usage: `/p project_name project_code` E.g. `/p "New year\'s party" NYP`. \nNote: If project_name contains more than one word, enclose it in quotes (`"`).',
            ]);
        }
        $name = $this->args[0];
        $code = $this->args[1];

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
            'text' => "Project $name created!\nUse `/t create $code` to add a new task.",
        ]);
    }
}
