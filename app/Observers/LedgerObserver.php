<?php

namespace App\Observers;

use App\Models\BlockLedger;
use App\Traits\BlockchainHashable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LedgerObserver
{
    use BlockchainHashable;

    // --- Metode Utama ---

    /**
     * Tangani event 'created' (data baru disimpan).
     */
    public function created(Model $model): void
    {
        $this->createBlock($model, 'created');
    }

    /**
     * Tangani event 'updated' (data diubah).
     */
    public function updated(Model $model): void
    {
        // Hanya membuat blok jika atribut penting berubah.
        // Anda dapat menambahkan logika khusus di sini (misal: if ($model->isDirty(['amount', 'status'])) )
        $this->createBlock($model, 'updated');
    }

    // --- Logika Pembuatan Blok ---

    protected function createBlock(Model $model, string $eventType): void
    {
        // --- 1. Siapkan Data ---
        
        // Logika untuk hanya mencatat perubahan (getChanges) saat update
        if ($eventType === 'created') {
            $attributes = $model->getAttributes();
        } else {
            // Untuk event 'updated', catat hanya atribut yang berubah (lebih efisien)
            $attributes = $model->getChanges(); 
        }
        
        $dataToBlock = [
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'event' => $eventType,
            'attributes' => $attributes, // Gunakan hasil seleksi (all/changes)
        ];

        $timestamp = Carbon::now()->toDateTimeString();
        
        // Mengambil hash blok sebelumnya (akan null untuk Blok Genesis)
        $previousHash = $this->getLastHash(); 
        
        // 2. Hitung Current Hash
        $currentHash = $this->calculateHash($dataToBlock, $timestamp, $previousHash);

        // 3. Buat Blok Baru di Ledger
        BlockLedger::create([
            'data' => json_encode($dataToBlock),
            'timestamp' => $timestamp,
            'previous_hash' => $previousHash,
            'current_hash' => $currentHash,
        ]);
    }
}