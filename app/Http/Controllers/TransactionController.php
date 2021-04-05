<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * TransactionController constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function create(TransactionRequest $request): JsonResponse
    {
        try {
            $amount = (float) $request->get('amount');
            $payer = (int) $request->get('payer');
            $payee = (int) $request->get('payee');

            if ($amount <= 0) {
                throw new Exception('O valor da transferência não pode ser menor ou igual a 0');
            }

            $transaction = $this->transaction->createTransaction($payer, $payee, $amount);

            return response()->json([
                'message' => 'Valor enviado com sucesso',
                'transaction' => $transaction
            ], JsonResponse::HTTP_CREATED);
        } catch (InvalidArgumentException $e) {
            throw new HttpResponseException(
                response()->json([
                    'error' => $e->getMessage()
                ], JsonResponse::HTTP_BAD_REQUEST)
            );
        } catch (ModelNotFoundException $e) {
            throw new HttpResponseException(
                response()->json([
                    'error' => $e->getMessage()
                ], JsonResponse::HTTP_NOT_FOUND)
            );
        } catch (Exception $e) {
            throw new HttpResponseException(
                response()->json([
                    'error' => $e->getMessage()
                ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
            );
        }
    }
}
