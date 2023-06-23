<?php

namespace Bengr\Admin\Models;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Auth\Concerns\HasAuthTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdminUser extends Authenticatable implements HasMedia
{
    use HasAuthTokens, HasFactory, Notifiable, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password'];

    protected $hidden = ['password'];

    public function tokens()
    {
        return $this->morphMany(BengrAdmin::authTokenModel(), 'tokenable');
    }

    public function firstName(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::ucfirst($value)
        );
    }

    public function lastName(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::ucfirst($value)
        );
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}"
        );
    }

    public function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value)
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'username', 'email', 'password', 'deleted_at'])
            ->logOnlyDirty();
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->useFallbackUrl(config('app.url') . '/media/profile_image.jpg')
            ->singleFile();
    }
}
