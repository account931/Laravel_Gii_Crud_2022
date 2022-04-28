<?php
//Model User for Passport Api (user in REST API). 99% the same as model User, except for 2 lines =>   public $guard_name = 'api'; // Passport Api  protected $table   = 'users';
//The only purpose of this model is to use public $guard_name = 'api'; instead of public $guard_name = 'web'; 
namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; //Spatie Laravel RBAC Permission
use Laravel\Passport\HasApiTokens; // Passport Api

class User_For_Passport extends Authenticatable
{
    use Notifiable;
	use HasRoles; //Spatie Laravel RBAC Permission
	use HasApiTokens; // Passport Api

    public $guard_name = 'api';    // Passport Api change
	protected $table   = 'users';  // Passport Api change
	 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
