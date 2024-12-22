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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('price', 10, 2)->nullable()->default(0.00);
            $table->float('sub_total')->nullable();
            $table->foreignId('table_id')->nullable()->constrained('tables')->default(0)->onDelete('cascade');
            // $table->integer('user_id')->nullable()->constrained('users')->default(0)->onDelete('cascade');;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
