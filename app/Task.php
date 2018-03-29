<?php

namespace App;

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
}
