<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Block;

class ProductObserver
{
    public function created(Product $product)
    {
        $lastBlock = Block::orderBy('index', 'desc')->first();

        if (!$lastBlock) {
            $index = 0;
            $previousHash = '00000000000000000000000000000000';
        } else {
            $index = $lastBlock->index + 1;
            $previousHash = $lastBlock->hash;
        }

        // Siapkan data Array
        $dataArray = [
            'action' => 'PRODUCT_CREATED',
            'product_id' => $product->id,
            'product_name' => $product->title,
            'price' => $product->price,
            'created_by' => auth()->user() ? auth()->user()->name : 'System'
        ];
        
        // KONVERSI KE STRING JSON (PENTING!)
        $dataJson = json_encode($dataArray);
        
        // KONVERSI WAKTU KE STRING (PENTING!)
        $timestamp = now()->format('Y-m-d H:i:s'); 

        // RAKIT HASH
        $stringToHash = $index . $timestamp . $dataJson . $previousHash;
        $hash = hash('sha256', $stringToHash);

        // SIMPAN
        Block::create([
            'index' => $index,
            'timestamp' => $timestamp,
            'data' => $dataJson, // Kita simpan string mentah
            'previous_hash' => $previousHash,
            'hash' => $hash
        ]);
    }
}