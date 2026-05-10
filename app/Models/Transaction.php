<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'user_id',
        'type',
        'quantity',
        'description'
    ];

    // Relasi ke Asset agar nama barang bisa muncul di laporan
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // Relasi ke User untuk tahu siapa yang mencatat
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}