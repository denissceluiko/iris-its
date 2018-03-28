<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;

class TeamController extends MattermostController
{
    protected $defaultView = 'mattermost.team.help';

    public function optionInit()
    {
        $team = Team::fromMattermost($this->request->team_id)->first();
        if (!$team) {
            $team = Team::create([
                'mm_id' => $this->request->team_id,
                'mm_domain' => $this->request->team_domain,
            ]);

            return $this->response("Team `$team->mm_domain` has been initialized.");
        }
        else
        {
            return $this->response("Team `$team->mm_domain` already exists.");
        }
    }
}
