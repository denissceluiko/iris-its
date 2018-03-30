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

    protected $defaultView = 'mattermost.project.help';

    /**
     * ProjectController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->team = Team::fromMattermost($request->team_id)->first();
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
            return $this->usage("`$this->command new project_code project_name` E.g. `$this->command new NYP New year\'s party`.");
        }
        $code = mb_strtoupper($this->args[0]);
        $name = implode(' ', array_slice($this->args, 1));
        $next_task_number = 1;

        $project = $this->team->projects()->withName($name)->first();
        if ($project)
        {
            return $this->response("Project named $name already exists in {$this->team->mm_domain}.");
        }

        $project = $this->team->projects()->withCode($code)->first();
        if ($project)
        {
            return $this->response("Project with a code `$code` already exists in {$this->team->mm_domain}.");
        }

        $this->team->projects()->create(compact('name', 'code', 'next_task_number'));

        return $this->response("Project $name created! Use `/t new $code` to add a new task.");
    }

    public function optionAdd()
    {
        return $this->optionNew();
    }

    public function optionCreate()
    {
        return $this->optionNew();
    }

    public function optionList()
    {
        return $this->response([
            'team' => $this->team,
            'projects' => $this->team->projects()->get()
        ], 'mattermost.project.list');
    }
}
