<?php

namespace App\Mattermost;

use App\Team;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
