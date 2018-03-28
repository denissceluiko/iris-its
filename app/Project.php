<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @param Builder $query
     * @param string $code
     * @return Builder
     */
    public function scopeWithCode(Builder $query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeWithName(Builder $query, $name)
    {
        return $query->where('name', $name);
    }
}
