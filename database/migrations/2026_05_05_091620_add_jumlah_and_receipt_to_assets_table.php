<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Menambahkan kolom jumlah setelah kolom name_asset
            $table->integer('jumlah')->default(0)->after('name_asset');
            
            // Memastikan kolom receipt_file juga ada (jika belum ada)
            if (!Schema::hasColumn('assets', 'receipt_file')) {
                $table->string('receipt_file')->nullable()->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'receipt_file']);
        });
    }
};