<?php

namespace Bengr\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettingsLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['settings_id', 'code', 'is_default'];

    protected $casts = [
        'is_default' => 'bool'
    ];
}
