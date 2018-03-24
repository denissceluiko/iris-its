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

            return response()->json([
                'response_type' => 'ephemeral',
                'text' => "Team `$team->mm_domain` has been initialized."
            ]);
        }
        else
        {
            return response()->json([
                'response_type' => 'ephemeral',
                'text' => "Team `$team->mm_domain` already exists."
            ]);
        }
    }

    /**
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function optionDump(Request $request)
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => 'Requested params: '.json_encode([
                'channel_id' => $request->channel_id,
                'channel_name' => $request->channel_name,
                'command' => $request->command,
                'team_domain' => $request->team_domain,
                'team_id' => $request->team_id,
                'text' => $request->text,
                'token' => $request->token,
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
            ]),
        ]);
    }
}
