<?php

namespace Bengr\Admin\Models;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Auth\Concerns\HasAuthTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use HasAuthTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password'];

    protected $hidden = ['password'];

    public function tokens()
    {
        return $this->morphMany(BengrAdmin::authTokenModel(), 'tokenable');
    }
}
