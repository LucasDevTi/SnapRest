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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string(column: 'name');
            $table->string(column: 'email')->nullable();
            $table->string(column: 'cpf_cnpj')->nullable();
            $table->string(column: 'phone_1')->nullable();
            $table->string(column: 'cellphone')->nullable();
            $table->date(column: 'birth_date')->nullable();
            $table->text(column: 'obs')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
