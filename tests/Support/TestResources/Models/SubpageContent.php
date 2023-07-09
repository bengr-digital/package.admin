<?php

namespace Bengr\Admin\Tests\Support\TestResources\Models;

use Bengr\Support\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubpageContent extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $fillable = ['subpage_id', 'code', 'text'];

    public $translatable = ['code', 'text'];

    public function subpage(): BelongsTo
    {
        return $this->belongsTo(Subpage::class);
    }
}
