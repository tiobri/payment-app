<?php

namespace App\Models;

use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_wallet',
        'payee_wallet',
        'amount'
    ];

    /**
     * @param int $payer
     * @param int $payee
     * @param float $amount
     * @return Transaction
     * @throws Exception
     */
    public function createTransaction(int $payer, int $payee, float $amount): Transaction
    {
        try {
            DB::beginTransaction();

            $Payer = Wallet::getUserWallet($payer);
            $Payee = Wallet::getUserWallet($payee);

            $Payer->canTransfer($amount);

            $this->isAuthorized();

            $transaction = Transaction::create([
                'payer_wallet' => $Payer->user_id,
                'payee_wallet' => $Payee->user_id,
                'amount' => $amount
            ]);

            $Payer->updateAmount($transaction->amount * -1);
            $Payee->updateAmount($transaction->amount);

            DB::commit();

            $this->sendNotification($Payee->user_id);

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function isAuthorized(): void
    {
        $resource = curl_init();
        curl_setopt($resource, CURLOPT_URL, 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);

        $result = json_decode(curl_exec($resource), true);

        curl_close($resource);

        if ($result['message'] != 'Autorizado') {
            throw new Exception('Transação não autorizada');
        }
    }

    /**
     * @param $userId
     */
    private function sendNotification($userId)
    {
        try {
            $resource = curl_init();
            curl_setopt($resource, CURLOPT_URL, 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
            curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);

            $result = json_decode(curl_exec($resource), true);

            curl_close($resource);

            if ($result['message'] != 'Enviado') {
                throw new Exception('Ocorreu um erro ao enviar a notificação');
            }
        } catch (Exception $e) {
            //TODO: SALVAR LOGS
            //AVISAR PROBLEMA DE NOTIFICAÇÕES
        }
    }
}
