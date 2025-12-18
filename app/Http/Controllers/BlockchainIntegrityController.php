<?php

namespace App\Http\Controllers;
use App\Services\BlockchainVerifierService;

class BlockchainIntegrityController extends Controller
{
    public function index(BlockchainVerifierService $verifier)
    {
        $result = $verifier->verify();

        return view('admin.blockchain-integrity', $result);
    }
    
}
