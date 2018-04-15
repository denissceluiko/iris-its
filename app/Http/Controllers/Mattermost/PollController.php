<?php

namespace App\Http\Controllers\Mattermost;

use App\Mattermost\Attachment;
use Illuminate\Http\Request;
use App\Http\Controllers\Mattermost\MattermostController;

class PollController extends MattermostController
{
    protected $aliases = [];

    protected $defaultView = 'mattermost.task.help';

    public function optionNew()
    {
        $attachment = new Attachment();

        $attachment->title('Example poll')->fallback('Empty fallback.');

        $attachment->action('Option 1', [
            'pollid' => 'testpoll',
            'option' => 'option1',
        ], env('APP_URL').'/poll/action');
        $attachment->action('Option 2', [
            'pollid' => 'testpoll',
            'option' => 'option2',
        ], env('APP_URL').'/poll/action');
        $attachment->action('Option 3', [
            'pollid' => 'testpoll',
            'option' => 'option3',
        ], env('APP_URL').'/poll/action');
        return $this->attach($attachment)->response();
    }
}
