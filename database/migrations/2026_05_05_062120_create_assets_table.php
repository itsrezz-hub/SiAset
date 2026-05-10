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
            Schema::create('assets', function (Blueprint $table) {
                $table->id();
                $table->string('asset_code')->unique();
                $table->string('name_asset');
                $table->string('brand')->nullable();
                $table->string('category');
                $table->string('room');
                $table->string('source_asset'); // Kolom Sumber Barang (Bisa diketik)
                $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
                $table->decimal('price', 15, 2);
                $table->date('purchase_date');
                $table->string('receipt_file')->nullable(); // Kolom Nota (Dokumentasi Rahasia)
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
