<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function create(UserRequest $request): JsonResponse
    {
        $user = User::create($request->only('name', 'cpf_cnpj', 'email', 'password'));
        Wallet::createWallet($user->id);

        return response()->json($user, JsonResponse::HTTP_CREATED);
    }
}
