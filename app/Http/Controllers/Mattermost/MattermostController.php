<?php

namespace App\Http\Controllers\Mattermost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MattermostController extends Controller
{
    protected $request;
    protected $helpMessage = 'Placeholder help message';


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Route a user's request.
     *
     * @return \Illuminate\Http\Response
     */
    public function router()
    {
        if ($this->request->has('text'))
        {
            $options = explode(' ', $this->request->text);
            $method = 'option'.$options[0];

            if (method_exists($this, $method))
            {
                return $this->$method($this->request);
            }
        }
        return $this->optionHelp($this->request);
    }

    /**
     * Provides help reference for the command group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionHelp()
    {
        return response()->json([
            'response_type' => 'ephemeral',
            'text' => $this->helpMessage,
        ]);
    }
}
