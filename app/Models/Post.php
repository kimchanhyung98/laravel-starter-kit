<?php

namespace App\Models;

use App\Models\Scopes\ViewablePostScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'contents',
        'is_open',
    ];

    protected $hidden = [
        'is_open',
        'deleted_at',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ViewablePostScope);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
