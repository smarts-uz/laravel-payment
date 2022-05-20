<?php

namespace Teamprodev\LaravelPayment\Services;

use Teamprodev\LaravelPayment\Models\Transaction;

class TransactionService
{

    /**
     * @param $transaction_id
     * @return mixed
     */
    public static function getTransactionById($transaction_id)
    {
        return Transaction::find($transaction_id);
    }
}
