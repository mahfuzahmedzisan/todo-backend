<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $with = [
        'user',
    ];
    protected $fillable = [
        'title',
        'description',
        'completed',
        'user_id',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const COMPLETED = 1;
    public const NOT_COMPLETED = 0;

    public function completed(): bool
    {
        return $this->completed == self::COMPLETED;
    }

    public function notCompleted(): bool
    {
        return $this->completed == self::NOT_COMPLETED;
    }

    protected $appends = [
        'completed_at_formatted',
        'due_date_formatted',
        'created_at_formatted',
        'updated_at_formatted',
    ];

    public function getCompletedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->completed_at)->format('M d, Y h:i:s A');
    }

    public function getDueDateFormattedAttribute(): string
    {
        return Carbon::parse($this->due_date)->format('M d, Y h:i:s A');
    }

    public function getCreatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('M d, Y h:i:s A');
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->updated_at)->format('M d, Y h:i:s A');
    }
}
