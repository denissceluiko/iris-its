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

    protected $helpView = 'mattermost.task.help';

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
            return $this->response("Usage: `$this->command new project_code project_name` E.g. `$this->command new NYP New year\'s party`.");
        }
        $code = $this->args[0];
        $name = implode(' ', array_slice($this->args, 1));

        $project = $this->team->projects()->where('name', $name)->first();
        if ($project)
        {
            return $this->response("Project named $name already exists in this team.");
        }

        $project = $this->team->projects()->where('code', $code)->first();
        if ($project)
        {
            return $this->response("Project with a code `$code` already exists in this team.");
        }

        $this->team->projects()->create(compact('name', 'code'));

        return $this->response("Project $name created! Use `/t new $code` to add a new task.");
    }
}
