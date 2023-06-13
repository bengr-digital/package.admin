<?php

namespace Bengr\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;

class AdminSettings extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'email'];

    public function billing()
    {
        return $this->hasOne(AdminSettingsBilling::class, 'settings_id');
    }

    public function socials()
    {
        return $this->hasMany(AdminSettingsSocial::class, 'settings_id');
    }

    public function languages()
    {
        return $this->hasMany(AdminSettingsLanguage::class, 'settings_id');
    }
}
