<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    // *** INI ADALAH PERBAIKAN KRUSIAL UNTUK MENGHILANGKAN QUERY EXCEPTION ***
    protected $fillable = [
        'title',       // MENGGANTI 'name'
        'description',
        'price',
        // 'stock' DIHAPUS KARENA TIDAK ADA DI DB
        'category',    // MENGGANTI 'category_id'
        'image',       // MENGGANTI 'image_url'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at', // Tambahkan jika menggunakan SoftDeletes
    ];

    public function category()
    {
        // Pastikan relasi juga menggunakan nama Model yang benar jika berbeda
        return $this->belongsTo(Category::class, 'category', 'id'); 
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2, ',', '.');
    }

    public function isAvailable()
    {
        // PERINGATAN: Karena 'stock' dihapus dari DB, fungsi ini akan error 
        // kecuali Anda menambahkannya kembali lewat migration.
        // Jika tidak, Anda harus menghapus/mengomentari fungsi ini:
        // return $this->stock > 0;
    }
}
