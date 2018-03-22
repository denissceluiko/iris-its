<?php

namespace App\Http\Controllers\Mattermost;

use Illuminate\Http\Request;

class TeamController extends MattermostController
{
    protected $helpMessage = <<<EOT
Use `/team` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| dump    | Dumps request data |

For example `/team help` displays this message.
EOT;

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
