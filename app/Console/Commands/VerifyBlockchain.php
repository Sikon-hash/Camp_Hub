<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlockLedger;
use App\Traits\BlockchainHashable;

class VerifyBlockchain extends Command
{
    use BlockchainHashable;

    /**
     * Nama command
     */
    protected $signature = 'blockchain:verify';

    /**
     * Deskripsi command
     */
    protected $description = 'Memverifikasi integritas seluruh rantai blockchain (BlockLedger)';

    public function handle(): int
    {
        $blocks = BlockLedger::orderBy('id', 'asc')->get();

        if ($blocks->isEmpty()) {
            $this->warn("⚠️ Rantai Ledger kosong. Tidak ada yang diverifikasi.");
            return Command::SUCCESS;
        }

        $this->info("🔍 Memverifikasi {$blocks->count()} blok...\n");

        foreach ($blocks as $index => $currentBlock) {

            /* =============================
             * 1. Verifikasi previous_hash
             * ============================= */
            if ($index > 0) {
                $previousBlock = $blocks[$index - 1];

                if ($currentBlock->previous_hash !== $previousBlock->current_hash) {
                    $this->error("❌ [BLOK #{$currentBlock->id}] Rantai PUTUS!");
                    $this->line("   Previous Hash Saat Ini : {$currentBlock->previous_hash}");
                    $this->line("   Current Hash Sebelumnya: {$previousBlock->current_hash}");
                    return Command::FAILURE;
                }
            } else {
                // Genesis block
                if (!empty($currentBlock->previous_hash)) {
                    $this->warn("⚠️ [BLOK GENESIS] previous_hash seharusnya NULL / kosong.");
                }
            }

            /* =============================
             * 2. Verifikasi current_hash
             * ============================= */
            $recalculatedHash = $this->generateHash($currentBlock);

            if ($currentBlock->current_hash !== $recalculatedHash) {
                $this->error("❌ [BLOK #{$currentBlock->id}] DATA TELAH DIMODIFIKASI!");
                $this->line("   Hash di Database : {$currentBlock->current_hash}");
                $this->line("   Hash Perhitungan : {$recalculatedHash}");
                return Command::FAILURE;
            }

            $this->info("✅ [BLOK #{$currentBlock->id}] Valid");
        }

        $this->info("\n🎉 VERIFIKASI BERHASIL: Seluruh rantai blockchain VALID.");
        return Command::SUCCESS;
    }
}
