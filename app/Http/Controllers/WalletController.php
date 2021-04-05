<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    /**
     * @param WalletRequest $request
     * @return JsonResponse
     */
    public function addFunds(WalletRequest $request): JsonResponse
    {
        $Wallet = Wallet::getUserWallet($request->get('user_id'));
        $Wallet->updateAmount((float) $request->get('amount'));

        return response()->json([
            'message' => 'Valor adicionado à carteira',
            'wallet' => $Wallet
        ], JsonResponse::HTTP_CREATED);
    }
}
