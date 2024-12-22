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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained('tables')->default(0)->onDelete('cascade');
            $table->integer('status_payment')->nullable();
            $table->string('description_status')->nullable();
            $table->float('total_value', 10, 2)->nullable()->default(0.00);
            // $table->foreignId('user_id')->nullable()->constrained('users')->default(0)->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
