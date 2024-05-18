<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'title', 'contents', 'is_open',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'contents' => $this->contents,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function scopeOpen(Builder $query): void
    {
        $query->where('is_open', true);
    }
}
