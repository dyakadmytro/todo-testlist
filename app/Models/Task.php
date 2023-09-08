<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'task_list_id', 'parent', 'name', 'description', 'status', 'priority', 'completed_at'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function parentTask() {
        return $this->belongsTo(Task::class, 'parent', 'id');
    }

    public function childs() {
        return $this->hasMany(Task::class, 'parent', 'id');
    }
}
