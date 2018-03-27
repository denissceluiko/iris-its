<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['mm_id', 'mm_domain'];

    public function projects()
    {
        return $this->hasMany(Project::class);
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
