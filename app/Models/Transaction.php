<?php

namespace App\Models;

use App\Helpers\Currency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Database\Factories\TransactionFactory;

#[UseFactory(TransactionFactory::class)]
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'bank_id', 'type', 'amount', 'description', 'notes', 'date', 'reference_month'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'reference_month' => 'date:Y-m',
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        // Sem mês de referência informado, a transação conta no mês da própria data
        static::saving(function (Transaction $transaction) {
            if (empty($transaction->getAttributes()['reference_month'])) {
                $transaction->reference_month = $transaction->date;
            }
        });
    }

    /**
     * Aceita "AAAA-MM", uma data completa ou Carbon, e guarda sempre o dia 1 do mês.
     */
    public function setReferenceMonthAttribute($value): void
    {
        if (blank($value)) {
            $this->attributes['reference_month'] = null;
            return;
        }

        $date = $value instanceof \DateTimeInterface
            ? Carbon::instance($value)
            : Carbon::parse(strlen($value) === 7 ? "{$value}-01" : $value);

        $this->attributes['reference_month'] = $date->startOfMonth()->toDateString();
    }

    public function getFormattedAmountAttribute(): string
    {
        return Currency::format($this->amount);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function scopeFromBank($query, ?int $bankId)
    {
        return $bankId ? $query->where('bank_id', $bankId) : $query;
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeInMonth($query, int $month, int $year)
    {
        return $query->where('reference_month', sprintf('%04d-%02d-01', $year, $month));
    }
}
