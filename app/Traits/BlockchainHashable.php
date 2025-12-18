<?php

namespace App\Traits;

use App\Models\BlockLedger; // PENTING: Memastikan Model ini di-import
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

trait BlockchainHashable
{
    /**
     * Mengambil hash dari blok terakhir di ledger.
     */
    protected function getLastHash(): ?string
    {
        $lastBlock = BlockLedger::latest()->first();

        // Blok pertama di rantai akan memiliki previous_hash null
        return $lastBlock ? $lastBlock->current_hash : null;
    }

    /**
     * Merancang algoritma SHA-256.
     * Rumus: Hash = SHA256(Data + Timestamp + PreviousHash)
     *
     * @param array $data Data yang akan dimasukkan ke blok.
     * @param string $timestamp Waktu pembuatan blok.
     * @param string|null $previousHash Hash blok sebelumnya.
     * @return string Hash SHA-256 
     */
    protected function calculateHash(array $data, string $timestamp, ?string $previousHash): string
    {
        // Konversi data menjadi string JSON dan gabungkan dengan timestamp & previousHash
        $payload = json_encode($data) . $timestamp . ($previousHash ?? '');

        // Hitung hash menggunakan algoritma SHA-256
        return hash('sha256', $payload);
    }
}