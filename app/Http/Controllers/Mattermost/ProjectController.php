<?php

namespace App\Http\Controllers\Mattermost;

use Illuminate\Http\Request;

class ProjectController extends MattermostController
{
    protected $helpMessage = <<<EOT
Use `/p` with following options:

| Command | Usage             |
| :------ | :---------------- |
| help    | Show this message |

For example `/p help` displays this message.
EOT;

}
