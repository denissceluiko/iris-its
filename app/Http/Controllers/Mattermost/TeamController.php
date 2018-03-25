<?php

namespace App\Http\Controllers\Mattermost;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends MattermostController
{
    protected $helpView = 'mattermost.team.help';

    public function optionInit()
    {
        $team = Team::where('mm_id', $this->request->team_id)->first();
        if (!$team)
        {
            $team = Team::create([
                'mm_id' => $this->request->team_id,
                'mm_domain' => $this->request->team_domain,
                'user_id' => 1,
            ]);

            return $this->response("Team `$team->mm_domain` has been initialized.");
        }
        else
        {
            return $this->response("Team `$team->mm_domain` already exists.");
        }
    }
}
