<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fb_id'
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
     * get all admin users
     * @retrun \Illuminate\Database\Eloquent\Collection
     */
    public function getAllADminUsers()
    {
        return self::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'Admin');
            }
        )->get();
    }

    /**
     * sent email
     * @param User $user
     * @param User $noRoleUser
     * @return void
     */
    public function sendAssignRoleEmail(User $user, User $noRoleUser)
    {
        if ($user && $noRoleUser) {
            Mail::send('email.assign_role_email', ['user' => $user, 'noRoleUser' => $noRoleUser], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject(__('Admin users notification'));
                $message->from('no-reply@shouts.dev', 'Shouts.dev');
            });
        }
    }
}
