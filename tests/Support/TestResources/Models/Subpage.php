<?php

namespace Bengr\Admin\Tests\Support\TestResources\Models;

use Bengr\Support\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subpage extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['title', 'description', 'keywords', 'path', 'is_active', 'name_code'];

    public $translatable = ['title', 'description', 'keywords', 'path'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function keywords(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => implode(', ', $value),
            set: fn ($value) => ['keywords' => $value ? array_map('trim', explode(',', $value)) : []]
        );
    }

    public function contents()
    {
        return $this->hasMany(SubpageContent::class, 'subpage_id');
    }
}
