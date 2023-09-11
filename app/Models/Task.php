<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

class Task extends Model
{
    use HasFactory, Searchable;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'task_list_id', 'parent', 'title', 'description', 'status', 'priority', 'completed_at'
    ];

    public function taskList()
    {
        return $this->belongsTo(TaskList::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent', 'id');
    }

    public function childs()
    {
        return $this->hasMany(Task::class, 'parent', 'id');
    }

    public function scopeOfStatus(Builder $query, string $status)
    {
        $query->where('status', $status);
    }

    public function scopePriority(Builder $query, int $from, int $to)
    {
        $query->whereBetween('priority', [$from, $to]);
    }

    public function scopeTitle(Builder $query, string $text)
    {
        $query->whereIn('id',Task::search($text)->get()->pluck('id'));
    }

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description
        ];
    }
}
