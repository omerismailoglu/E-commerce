<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory; // kullanıcı modeli için
use Illuminate\Foundation\Auth\User as Authenticatable; // kullanıcı modeli için
use Illuminate\Notifications\Notifiable; // email gönderimi için
use Laravel\Sanctum\HasApiTokens; // token gönderimi için

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
        'password',
        'role',
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

    public function cart()
    {
        return $this->hasOne(Cart::class); //
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
