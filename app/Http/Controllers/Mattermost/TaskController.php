<?php

namespace App\Http\Controllers\Mattermost;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends MattermostController
{
    protected $helpMessage = <<<EOT
Use `/t` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| all     | Show all jobs     |

For example `/t help` displays this message.
EOT;


    public function optionAll(Request $request)
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }

    public function optionMy(Request $request)
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }

    public function optionTake(Request $request)
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => '',
        ]);
    }
}
