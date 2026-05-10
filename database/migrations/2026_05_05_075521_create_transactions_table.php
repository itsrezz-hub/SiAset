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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke tabel assets (sesuaikan jika nama tabelmu berbeda)
        $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
        // Menghubungkan ke tabel users (siapa yang input)
        $table->foreignId('user_id')->constrained('users');
        $table->enum('type', ['in', 'out']); // Masuk atau Keluar
        $table->integer('quantity');
        $table->text('description')->nullable();
        $table->timestamps(); // Ini penting untuk filter harian/mingguan/bulanan
    });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
