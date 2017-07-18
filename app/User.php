<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    use EntrustUserTrait;

    protected $fillable = ['id', 'username', 'nickname', 'avatar', 'password'];

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function restore()
    {
        $this->restoreEntrust();
        $this->restoreSoftDelete();
    }

    public function getAvatarAttribute($value)
    {
        if (!\Storage::exists($value)) {
            return $value;
        }
        return \Storage::url($value);
    }
}
