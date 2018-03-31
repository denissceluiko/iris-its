<?php

namespace App;

use App\Mattermost\Token;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['mm_id', 'mm_domain'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Project::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * Search for team with a specific Mattermost team id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $mm_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromMattermost($query, $mm_id)
    {
        return $query->where(['mm_id' => $mm_id]);
    }
}
