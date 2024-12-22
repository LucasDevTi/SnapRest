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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->nullable();
            $table->foreignId('linked_table_id')->nullable()->constrained('tables')->onDelete('set null');
            // $table->integer('linked_table_id')->default(0);
            $table->integer('status')->nullable();
            $table->string('description_status')->nullable();
            // $table->integer('user_id')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
