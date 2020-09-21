<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

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

    public function role() {
      return $this->belongsTo(Role::class);
    }

    public function setRole(Role $role) {
      $this->role_id = $role->id;
      return $this->save();
    }

    public function isNormal() {
      return $this->role->id == 2;
    }

    public function isNormalOrBetter() {
      return $this->role->id <= 2;
    }

    public function isAdmin() {
      return $this->role->id == 1;
    }

    public function isGuest() {
      return $this->role->id == 3;
    }
}
