<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon_id', 'color', 'type', 'is_default'];

    protected $appends = ['icon'];

    protected $with = ['iconRecord'];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function iconRecord(): BelongsTo
    {
        return $this->belongsTo(Icon::class, 'icon_id');
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->iconRecord?->icon ?? '',
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function scopeExpense($query)
    {
        return $query->whereIn('type', ['expense', 'both']);
    }

    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['income', 'both']);
    }
}