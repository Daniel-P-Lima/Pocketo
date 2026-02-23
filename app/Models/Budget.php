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

    public function getSpentAttribute(): int
    {
        return Transaction::where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->sum('amount');
    }

    public function getSpentPercentageAttribute(): float
    {
        if ($this->amount === 0) {
            return 0;
        }

        return min(100, round(($this->spent / $this->amount) * 100, 1));
    }

    public function getRemainingAttribute(): int
    {
        return max(0, $this->amount - $this->spent);
    }

    public function scopeForMonth($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
