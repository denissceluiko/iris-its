<?php

namespace App\Http\Controllers\Mattermost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MattermostController extends Controller
{
    protected $request;
    protected $args = null;
    protected $option = null;
    protected $helpMessage = 'Placeholder help message';


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
