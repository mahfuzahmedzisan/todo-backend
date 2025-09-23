<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $with = [
        'user',
    ];
    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'user_id',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const COMPLETED = 1;
    public const NOT_COMPLETED = 0;

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', self::COMPLETED);
    }

    public function scopeNotCompleted(Builder $query): Builder
    {
    return $query->where('is_completed', self::NOT_COMPLETED);
    }

    public function scopeWhereLike($query, $search)
    {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%$search%");
        });
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
