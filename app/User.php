<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['full_name'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_disabled'   => 'boolean'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function photos()
    {
        return $this->hasMany(Upload::class);
    }

    public function sendPasswordResetNotification($token)
    {
        // Send Password Reset Notification
        // Mail::to($this)->send(new PasswordReset($this, $token));
    }
}
