<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'code', 'creator_id', 'assignee_id', 'status', 'description', 'deadline_id'];
//    protected $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deadline_at',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class);
    }

    public function drop()
    {
        $this->assignee_id = null;
        $this->save();
    }

    /**
     * @param Builder $query
     * @param $code
     * @return Builder
     */
    public function scopeWithCode(Builder $query, $code)
    {
        return $query->where('tasks.code', $code);
    }
}
