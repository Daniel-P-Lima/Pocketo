<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stashes', function (Blueprint $table) {
            $table->id();
            $table->string('name', length: 255);
            $table->decimal('amount', total: 10, places: 2);
            $table->decimal('goal_amount', total: 10, places: 2);
            $table->string('purpose', length: 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stashes');
    }
};
