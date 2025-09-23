<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements OAuthenticatable, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'otp',
        'otp_sent_at',
        'otp_expires_at',
        'last_synced_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_sent_at',
        'otp_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'otp' => 'string',
            'otp_sent_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Appends 
    protected $appends = [
        'is_verified',
        'created_at_formatted',
        'updated_at_formatted',
        'last_synced_at_formatted',
    ];

    public const ADMIN = 1;
    public const NOT_ADMIN = 0;

    public function scopeAdmin(Builder $query): Builder
    {
        return $query->where('is_admin', self::ADMIN);
    }

    public function scopeNotAdmin(Builder $query): Builder
    {
        return $query->where('is_admin', self::NOT_ADMIN);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Attributes 
    public function getIsVerifiedAttribute(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function getCreatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->created_at)->format('M d, Y h:i:s A');
    }
    public function getUpdatedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->updated_at)->format('M d, Y h:i:s A');
    }
    public function getLastSyncedAtFormattedAttribute(): string
    {
        return Carbon::parse($this->last_synced_at)->format('M d, Y h:i:s A');
    }
}
