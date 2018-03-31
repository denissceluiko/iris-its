<?php

namespace App\Http\Controllers\Mattermost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MattermostController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Response status code
     *
     * @var int
     */
    private $statusCode = 200;

    /**
     * Slash command used by user.
     *
     * @var string
     */
    protected $command;

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * First argument of the slash command.
     *
     * @var string
     */
    protected $option;

    /**
     * The rest of the arguments of the slash command.
     *
     * @var array
     */
    protected $args;

    /**
     * View used as the help message.
     * Must be overridden by children.
     *
     * @var string
     */
    protected $defaultView = 'mattermost.help';

    /**
     * Used to determine if user's command should be included in the view
     *
     * @var bool
     */
    private $showRequest = false;

    /**
     * Used to determine if usage of the user's command should be included in the view
     *
     * @var bool
     */
    private $showUsage = false;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->command = $request->command;
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

        $this->showRequest = true;
        return $this->optionHelp();
    }

    /**
     * Splits 'text' argument from Mattermost.
     */
    protected function splitText() {
        if ($this->request->has('text') && mb_strlen($this->request->text) > 0)
        {
            preg_match_all("/([^\"\s]+)|\"([^\"]+)\"/u", $this->request->text, $matches);
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
     * Generates a response.
     *
     * Possibilities:
     * response('view.name')
     * response(['key' => 'value'], 'view.name')
     * response('Text')
     *
     * @param array|string $data
     * @param string $view
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($data, $view = null, $type = 'ephemeral')
    {
        $view = func_num_args() == 1 && View::exists($data) ? $data : (View::exists($view) ? $view : null);

        if ($view != null) // response('view.name') or response(['key' => 'value'], 'view.name')
        {
            $dataSuffix = [];
            if ($data != $view) // response(['key' => 'value'], 'view.name')
            {
                $dataSuffix = $data;
            }

            $data = array_merge([
                'mm' => $this,
                'showRequest' => $this->showRequest,
                'showUsage' => $this->showUsage
            ], $dataSuffix);
        }

        if ($view && is_array($data))
        {
            $message = View::make($view, $data)->render();
        }
        else
        {
            $message = $data;
        }

        return response()->json([
            'response_type' => $type,
            'text' => $message,
        ])->setStatusCode($this->statusCode);
    }

    /**
     * Returns the command user attempted and explanation of proper usage.
     *
     * @param string $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function usage($text)
    {
        $this->showRequest = true;
        $this->showUsage = true;
//        $this->statusCode = 401; // Will not use for now, might change depending on users.
        return $this->response(['usage' => $text], 'mattermost.response');
    }

    /**
     * Returns full slash command user sent
     *
     * @return string
     */
    public function userRequest()
    {
        return $this->command.' '.$this->request->text;
    }

    /**
     * Provides help reference for the command group
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionHelp()
    {
        return $this->response($this->defaultView);
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
