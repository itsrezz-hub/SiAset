<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     * Pastikan semua field ini sesuai dengan kolom di database (migration).
     */
    protected $fillable = [
    'asset_code', 
    'name_asset', 
    'jumlah', 
    'source_asset', 
    'category', 
    'room', 
    'condition', 
    'note', // Tambahkan ini
    'price', 
    'purchase_date', 
    'receipt_file'
    ];

    /**
     * Opsional: Jika kamu ingin Laravel otomatis menganggap purchase_date sebagai objek Carbon/Date
     */
    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
    ];
}