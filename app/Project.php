<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    protected $fillable = ['name', 'code', 'next_task_number'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @param array|Task $params
     * @return Task|null
     */
    public function newTask($params)
    {
        $params = is_a($params, Task::class) ? $params->toArray() : $params;
        if (!array_key_exists('name', $params)) return null;

        $args = [
            'name' => $params['name'],
            'code' => $this->code.'-'.$this->next_task_number,
            'description' => $params['description'] ?? null,
            'creator_id' => Auth::id(),
            'status' => $params['status'] ?? 'New',
            'assignee_id' => $params['assignee_id'] ?? null,
            'deadline_at' => $params['deadline_at'] ?? null,
        ];

        $task = new Task($args);

        $task = $this->tasks()->save($task);
        $this->increment('next_task_number');

        return $task;
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
