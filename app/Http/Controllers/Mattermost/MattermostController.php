<?php

namespace App\Http\Controllers\Mattermost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MattermostController extends Controller
{
    protected $request;
    protected $args = null;
    protected $option = null;
    protected $helpView = 'mattermost.help';


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->splitText();
    }

    /**
     * Route a user's request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function router()
    {
        if ($this->request->has('text'))
        {
            $method = 'option'.$this->option;

            if (method_exists($this, $method))
            {
                return $this->$method($this->request);
            }
        }
        return $this->optionHelp();
    }

    /**
     * Splits 'text' argument from Mattermost.
     */
    protected function splitText() {
        if ($this->request->has('text') && mb_strlen($this->request->text) > 0)
        {
            preg_match_all("/(\w+)|\"([^\"]+)\"/u", $this->request->text, $matches);
            $matches = $matches[0]; // We only need full pattern matches.

            // Cleanup
            foreach ($matches as &$match)
            {
                $match = trim($match, ' "');
            }

            $this->option = $matches[0] ?? null;
            $this->args = array_slice($matches, 1);
        }

    }

    /**
     * Generates a response
     *
     * @param array|string $data
     * @param string $view
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data, $view = null, $type = 'ephemeral')
    {
        $view = func_num_args() == 1 && View::exists($data) ? $data : (View::exists($view) ? $view : null);
        $data = $view == $data ? [] : $data; // response('view.name') called
        $message = ($view && is_array($data)) ? View::make($view, $data)->render() : (is_array($data) ? json_encode($data) : $data);

        return response()->json([
            'response_type' => $type,
            'text' => $message,
        ]);
    }

    /**
     * Provides help reference for the command group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionHelp()
    {
        return $this->response($this->helpView);
    }

    /**
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionDump(Request $request)
    {
        return $this->response('Requested params: '.json_encode([
                'channel_id' => $request->channel_id,
                'channel_name' => $request->channel_name,
                'command' => $request->command,
                'team_domain' => $request->team_domain,
                'team_id' => $request->team_id,
                'text' => $request->text,
                'token' => $request->token,
                'user_id' => $request->user_id,
                'user_name' => $request->user_name,
            ])
        );
    }
}
