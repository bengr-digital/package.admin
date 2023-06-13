<?php

namespace Bengr\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettingsSocial extends Model
{
    use HasFactory;

    protected $fillable = ['settings_id', 'name', 'url'];
}
