<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Route a user's request.
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


    public function optionAll(Request $request)
    {
        return response()->json([
            'response_type' => 'in_channel',
            'text' => '',
        ]);
    }

    public function optionMy(Request $request)
    {
        return response()->json([
            'response_type' => 'in_channel',
            'text' => '',
        ]);
    }

    public function optionTake(Request $request)
    {
        return response()->json([
            'response_type' => 'in_channel',
            'text' => '',
        ]);
    }

    public function optionHelp(Request $request)
    {
        return response()->json([
            'response_type' => 'in_channel',
            'text' => <<<EOT
Use `/t` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| all     | Show all jobs     |

For example `/t help` displays this message.
EOT
            ,
        ]);
    }


}
