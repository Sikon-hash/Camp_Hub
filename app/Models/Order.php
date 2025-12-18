<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // --- PERBAIKAN KRUSIAL UNTUK MENGHILANGKAN ERROR MASS ASSIGNMENT ---
    protected $fillable = [
    'name', 
    'rec_address', 
    'phone', 
    'user_id', 
    'product_id', 
    'quantity', // Tambahkan ini
    
    ];
    // ------------------------------------------------------------------

    // PERBAIKAN RELASI: Order dimiliki oleh User (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // PERBAIKAN RELASI: Order berhubungan dengan Product (belongsTo)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
