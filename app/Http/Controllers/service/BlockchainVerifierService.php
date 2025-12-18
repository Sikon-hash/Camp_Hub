<?php

namespace App\Services;

use App\Models\BlockLedger;
use App\Traits\BlockchainHashable;

class BlockchainVerifierService
{
    use BlockchainHashable;

    private function generateHash($block): string
    {
        return hash('sha256', json_encode([
            'id' => $block->id,
            'data' => $block->data,
            'previous_hash' => $block->previous_hash,
            'timestamp' => $block->timestamp
        ]));
    }

    public function verify(): array
    {
        $blocks = BlockLedger::orderBy('id', 'asc')->get();
        $total = $blocks->count();

        if ($total === 0) {
            return [
                'status' => 'warning',
                'message' => 'Rantai blockchain masih kosong.'
            ];
        }

        for ($i = 0; $i < $total; $i++) {
            $current = $blocks[$i];

            // GENESIS BLOCK
            if ($i === 0) {
                if ($current->previous_hash !== '0') {
                    return [
                        'status' => 'danger',
                        'message' => 'Genesis block tidak valid.'
                    ];
                }
                continue;
            }

            $previous = $blocks[$i - 1];

            if ($current->previous_hash !== $previous->current_hash) {
                return [
                    'status' => 'danger',
                    'message' => "Rantai terputus di blok #{$current->id}"
                ];
            }

            if ($this->generateHash($current) !== $current->current_hash) {
                return [
                    'status' => 'danger',
                    'message' => "Data dimodifikasi di blok #{$current->id}"
                ];
            }
        }

        return [
            'status' => 'success',
            'message' => 'Integritas blockchain valid.'
        ];
    }
}
