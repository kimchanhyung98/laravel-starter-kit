<?php

namespace App\Models;

use App\Models\Scopes\ViewablePostScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ScopedBy(ViewablePostScope::class)]
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
}
