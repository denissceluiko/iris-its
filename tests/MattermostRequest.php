<?php

namespace Tests;

use App\Mattermost\Token;
use App\Team;
use App\User;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

trait MattermostRequest
{
    protected $request = [];
    protected $noUser = false;
    protected $noTokens = false;
    private $trait_user;
    private $trait_team;

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

        $this->trait_user = $user;
        $this->request['user_id'] = $user->mm_id;
        $this->request['user_name'] = $user->name;
        return $this;
    }

    public function noUser()
    {
        $this->noUser = true;
        return $this;
    }

    public function team(Team $team = null)
    {
        if ($team == null)
        {
            $team = factory(Team::class)->create();
        }

        $this->trait_team = $team;
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
        if (!isset($this->request['user_id']) && $this->noUser == false)
        {
            $this->user();
        }

        if ($this->trait_team == null)
        {
            $this->team();
        }

        if ($this->noTokens == false)
        {
            $token = factory(Token::class)->make(['command' => $uri]);
            $this->request['token'] = $token->id;
            $this->trait_team->tokens()->save($token);
        }
        $this->request['command'] = $uri;
        return $this->post($uri, $this->request);
    }
}