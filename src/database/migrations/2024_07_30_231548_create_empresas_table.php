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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->onUpdate('no action')
                ->onDelete('cascade');
            $table->string('razao_social', 120);
            $table->string('nome_fantasia', 120)->nullable();
            $table->string('cnpj', 14)->unique();
            $table->enum('status', ['ativa', 'desabilitada', 'pendente'])->dafault('ativa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
