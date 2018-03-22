<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
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
Use `/p` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |

For example `/p help` displays this message.
EOT
            ,
        ]);
    }
}
