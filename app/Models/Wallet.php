<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount'
    ];

    /**
     * @param float $amount
     * @return void
     */
    public function updateAmount(float $amount): void
    {
        $this->update([
            'amount' => $this->amount + $amount
        ]);
    }

    /**
     * @param float $amount
     */
    public function canTransfer(float $amount): void
    {
        if (User::isShopman($this->user_id)) {
            throw new \InvalidArgumentException('O usuário não pode transferir pois é um lojista');
        }

        if (round($this->amount, 2) < round($amount, 2)) {
            throw new \InvalidArgumentException('Saldo Insuficiente');
        }
    }

    /**
     * @param $userId
     * @return Wallet
     */
    public static function createWallet($userId): Wallet
    {
        return Wallet::create(['user_id' => $userId]);
    }

    /**
     * @param $userId
     * @return Wallet
     */
    public static function getUserWallet($userId): Wallet
    {
        $Wallet = Wallet::where('user_id', '=', $userId)->first();

        if (!isset($Wallet)) {
            throw new ModelNotFoundException('Carteira não encontrada para o usuário ' . $userId);
        }

        return $Wallet;
    }
}
