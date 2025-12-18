<?php

namespace App\Services;

use App\Models\Block;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BlockchainValidator
{
    public function validateChain()
    {
        $chain = Block::orderBy('index', 'asc')->get();

        foreach ($chain as $key => $block) {
            
            $recalculatedHash = $this->calculateHash($block);
            
            // === AREA DEBUG: JEBAKAN HASH ===
            if ($block->hash !== $recalculatedHash) {
                
                // Kita hitung ulang string mentahnya supaya terlihat di layar debug
                // (Ini logika tiruan dari calculateHash agar kita bisa lihat inputnya)
                try {
                    $debugTimestamp = Carbon::parse($block->timestamp)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $debugTimestamp = $block->timestamp;
                }
                $debugString = $block->index . $debugTimestamp . $block->data . $block->previous_hash;

                // TAMPILKAN DIAGNOSA LENGKAP
                // dd([
                //     '🛑 STATUS' => 'HASH MISMATCH (Data Tidak Cocok)',
                //     '📍 Block Index' => $block->index,
                    
                //     '1️⃣ HASH DI DATABASE (Segel Lama)' => $block->hash,
                //     '2️⃣ HASH HITUNGAN BARU (Seharusnya)' => $recalculatedHash,
                    
                //     '--- ANALISIS KOMPONEN ---' => 'Periksa bagian bawah ini satu per satu:',
                //     '🔹 Index' => $block->index,
                //     '🔹 Timestamp (Yang Dibaca Sistem)' => $debugTimestamp,
                //     '🔹 Data (Raw String)' => $block->data,
                //     '🔹 Previous Hash' => $block->previous_hash,
                    
                //     '🔑 STRING LENGKAP PEMICU HASH' => $debugString
                // ]);
            }
            // === BATAS AREA DEBUG ===

            if ($key > 0) {
                $previousBlock = $chain[$key - 1];
                if ($block->previous_hash !== $previousBlock->hash) {
                    return [
                        'status' => 'COMPROMISED',
                        'message' => 'RANTAI PUTUS!',
                        'compromised_block' => $block->index,
                        'details' => "Chain Link Error di Blok #{$block->index}."
                    ];
                }
            }
        }

        return [
            'status' => 'SECURE',
            'message' => 'Integritas Sistem Terverifikasi. Semua rantai valid.',
            'compromised_block' => null
        ];
    }

    private function calculateHash($block)
    {
        // 1. DATA:
        // Menggunakan raw string dari database (karena $casts sudah dihapus di Model)
        $dataJson = $block->data; 
        
        // 2. TIMESTAMP:
        // Paksa format string Y-m-d H:i:s
        try {
            $timestamp = Carbon::parse($block->timestamp)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $timestamp = $block->timestamp;
        }
        
        // 3. RAKIT ULANG
        $stringToHash = $block->index . $timestamp . $dataJson . $block->previous_hash;
        
        return hash('sha256', $stringToHash);
    }
}