<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Проверяет, имеет ли пользователь роль администратора
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
    /**
     * Проверяет, имеет ли пользователь роль партнера
     *
     * @return bool
     */
    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }
    
    /**
     * Проверяет, имеет ли пользователь роль клиента
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Получить URL аватара пользователя.
     *
     * @return string
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar);
        }
        
        // Возвращаем URL аватара по умолчанию (Gravatar на основе email или placeholder)
        $emailHash = md5(strtolower(trim($this->email ?? '')));
        return $this->email ? "https://www.gravatar.com/avatar/{$emailHash}?d=mp&s=200" : asset('images/default-avatar.png');
    }
}
