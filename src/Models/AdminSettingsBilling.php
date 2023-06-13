<?php

namespace Bengr\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettingsBilling extends Model
{
    use HasFactory;

    protected $fillable = ['settings_id', 'name', 'country', 'city', 'zipcode', 'street', 'cin', 'tin'];
}
