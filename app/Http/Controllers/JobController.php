<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     *  Content-Length: 244
        User-Agent: Go 1.1 package http
        Host: localhost:5000
        Accept: application/json
        Content-Type: application/x-www-form-urlencoded

        channel_id=cniah6qa73bjjjan6mzn11f4ie&
        channel_name=town-square&
        command=/somecommand&
        response_url=not+supported+yet&
        team_domain=someteam&
        team_id=rdc9bgriktyx9p4kowh3dmgqyc&
        text=hello+world&
        token=xr3j5x3p4pfk7kk6ck7b4e6ghh&
        user_id=c3a4cqe3dfy6dgopqt8ai3hydh&
        user_name=somename
     *
    //        $this->validate($request, [
    //            'channel_id' => 'required',
    //            'channel_name' => 'required',
    //            'command' => 'required',
    //            'team_domain' => 'required',
    //            'team_id' => 'required',
    //            'text' => 'required',
    //            'token' => 'required',
    //            'user_id' => 'required',
    //            'user_name' => 'required',
    //        ]);
     * */

    /**
     * Route a user's request.
     *
     * @return \Illuminate\Http\Response
     */
    public function router(Request $request)
    {
//        dd($request);
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
Use `/j` with following options:
 
| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |
| all     | Show all jobs     |

For example `/j help` displays this message.
EOT
            ,
        ]);
    }


}
