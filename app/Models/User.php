<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'laboran_id'
    ];

    public function assistants()
    {
        return $this->hasMany(User::class, 'laboran_id');
    }

    // An Assistant belongs to a Laboran
    public function laboran()
    {
        return $this->belongsTo(User::class, 'laboran_id');
    }

    public function isLaboran(): bool
    {
        return $this->role === 'laboran';
    }

    // Get IDs of users whose data I can see
    public function getViewableUserIds(): array
    {
        if ($this->isLaboran()) {
            // Return My ID + My Assistants' IDs
            return $this->assistants()->pluck('id')->push($this->id)->toArray();
        }

        // If I am an assistant, I only see my own
        return [$this->id];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
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
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
