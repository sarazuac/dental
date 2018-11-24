<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Role;

class User extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','middle_name','last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
        {
        return $this->belongsToMany(Role::class);
        }

        public function authorizeRoles($roles)
        {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || 
                    abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) || 
                abort(401, 'This action is unauthorized.');
        }
        /**
        * Check multiple roles
        * @param array $roles
        */
        public function hasAnyRole($roles)
        {
        return null !== $this->roles()->whereIn(‘name’, $roles)->first();
        }
        /**
        * Check one role
        * @param string $role
        */
        public function hasRole($role)
        {
        return null !== $this->roles()->where(‘name’, $role)->first();
        }
}
