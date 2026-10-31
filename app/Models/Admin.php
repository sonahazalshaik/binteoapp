<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Mail\AdminPasswordResetMail;
use App\Services\MailService;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    protected static function booted()
    {
        static::addGlobalScope('admin', function ($builder) {
            $builder->where('role', 'admin');
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function firebaseTokens() {
        return $this->hasMany(FirebaseToken::class, 'admin_id');
    }

    /**
     * Override the default password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        try {
            app(MailService::class)->sendMailable($this->email, new AdminPasswordResetMail($this, $token));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin Password Reset Email Failed for ' . $this->email . ': ' . $e->getMessage());
        }
    }
}
