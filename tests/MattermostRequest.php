<?php

namespace Tests;

use App\Team;
use App\User;
use Illuminate\Support\Facades\Facade;

trait MattermostRequest
{
    protected $request = [];

    public function text($text)
    {
        $this->request['text'] = $text;
        return $this;
    }

    public function user(User $user = null)
    {
        if ($user == null)
        {
            $user = factory(User::class)->create();
        }

        $this->request['user_id'] = $user->mm_id;
        $this->request['user_name'] = $user->name;
        return $this;
    }

    public function team(Team $team = null)
    {
        if ($team == null)
        {
            $team = factory(Team::class)->create();
        }
        $this->request['team_id'] = $team->mm_id;
        $this->request['team_domain'] = $team->mm_domain;
        return $this;
    }

    /**
     * @param string $uri
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function send($uri)
    {
        $this->request['command'] = $uri;
        return $this->post($uri, $this->request);
    }
}