<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Route a user's request.
     * Handles /team [option]
     *
     * @return \Illuminate\Http\Response
     */
    public function router(Request $request)
    {
        if ($request->has('text'))
        {
            $options = explode(' ', $request->text);
            $method = 'option'.$options[0];

            if (method_exists($this, $method))
            {
                return $this->$method($request);
            }
        }
        return $this->optionHelp($request);
    }

    /**
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function optionDump(Request $request)
    {
        return request()->json([
            'response_type' => 'in_channel',
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

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionHelp(Request $request)
    {
        return response()->json([
            'response_type' => 'in_channel',
            'text' => <<<EOT
Use `/team` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| dump    | Dumps request data |

For example `/team help` displays this message.
EOT
            ,
        ]);
    }
}
