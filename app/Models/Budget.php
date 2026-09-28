<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'amount', 'month', 'year'];

    protected $appends = ['spent', 'spent_percentage'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'month' => 'integer',
            'year' => 'integer',
        ];
    }

    public function getFormattedAmountAttribute(): string
    {
        return Currency::format($this->amount);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAttribute(): float
    {
        return Transaction::where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->inMonth($this->month, $this->year)
            ->sum('amount');
    }

    public function getSpentPercentageAttribute(): float
    {
        // amount vem do cast decimal como string ("0.00"), por isso a comparação numérica
        if ((float) $this->amount <= 0) {
            return 0;
        }

        return min(100, round(($this->spent / $this->amount) * 100, 1));
    }

    public function getRemainingAttribute(): float
    {
        return max(0, round($this->amount - $this->spent, 2));
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
