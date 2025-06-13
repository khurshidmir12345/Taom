<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'google_id',
        'google_token',
        'google_refresh_token',
        'telegram_chat_id',
        'telegram_username',
        'telegram_first_name',
        'telegram_last_name',
        'is_telegram_verified',
        'last_message_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'is_telegram_verified' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function foods()
    {
        $this->belongsToMany(Vegetable::class, 'food_user');
    }

    function getCurrentMealType(): string
    {
        $hour = now()->hour;

        if ($hour < 10) {
            return 'Nonushta';
        } elseif ($hour >= 10 && $hour < 15) {
            return 'Tushlik';
        } else {
            return 'Kechki ovqat';
        }
    }

    public function hasTelegram()
    {
        return !is_null($this->telegram_chat_id);
    }

    public function getTelegramFullName()
    {
        if ($this->telegram_first_name && $this->telegram_last_name) {
            return $this->telegram_first_name . ' ' . $this->telegram_last_name;
        }
        return $this->telegram_first_name ?? $this->telegram_username ?? 'Unknown';
    }

    public function updateTelegramInfo($chatId, $username, $firstName, $lastName)
    {
        $this->update([
            'telegram_chat_id' => $chatId,
            'telegram_username' => $username,
            'telegram_first_name' => $firstName,
            'telegram_last_name' => $lastName,
            'is_telegram_verified' => true,
        ]);
    }

    public function updateLastMessageId($messageId)
    {
        $this->update(['last_message_id' => $messageId]);
    }
}
