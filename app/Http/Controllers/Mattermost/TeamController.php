<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;

class TeamController extends MattermostController
{
    protected $aliases = [
    ];

    protected $defaultView = 'mattermost.team.help';

    public function optionInit()
    {
        $team = Team::fromMattermost($this->request->team_id)->first();
        if (!$team) {

            $team = Team::where('mm_domain'. $this->request->team_name)->first();
            $team->mm_id = $this->request->team_id;
            $team->save();

            return $this->response("Team `$team->mm_domain` initialization has been finalized.");
        }
        else
        {
            return $this->response("Team `$team->mm_domain` is already initialized.");
        }
    }
}
