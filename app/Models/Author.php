<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mother_country',
        'comment',
        'active',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected static function booted() {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('active', true);
        });
    }

    public function scopeHidden($query)
    {
        return $query->where('active', '=', 0);
    }

    public function books(): BelongsTo
    {
        return $this->belongsTo(Book::class)
            ->withTimestamps();
    }
}
