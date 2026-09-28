<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mês em que a transação conta no orçamento. Para compras no cartão é o mês de
 * vencimento da fatura; para o resto, o próprio mês da data. Guardado como AAAA-MM-01.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('reference_month')->nullable()->after('date');
        });

        DB::table('transactions')->orderBy('id')->chunkById(500, function ($transactions) {
            foreach ($transactions as $transaction) {
                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update(['reference_month' => substr($transaction->date, 0, 7) . '-01']);
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->date('reference_month')->nullable(false)->change();
            $table->index(['reference_month', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['reference_month', 'type']);
            $table->dropColumn('reference_month');
        });
    }
};
