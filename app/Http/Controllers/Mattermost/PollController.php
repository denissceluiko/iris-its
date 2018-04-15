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
        $attachment->color('#ff0000')->text('Kektachment text')->fallback('kektachment');
        return $this->attach($attachment)->response('');
    }
}
