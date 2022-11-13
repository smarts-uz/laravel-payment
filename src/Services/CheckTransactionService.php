<?php

namespace Teamprodev\LaravelPayment\Services;

use Teamprodev\LaravelPayment\Models\TransactionForCheck;

class CheckTransactionService
{

    public static function check($transactionForCheck): bool
    {
        $transaction = $transactionForCheck->transaction;
        if (now()->diffInMinutes($transactionForCheck->created_at) > config('payuz')['invoice_life_time']) {
            PaymeReceiptsService::receiptCancel($transaction);
        }

        $response = PaymeReceiptsService::receiptCheck($transaction);
        $state = $response['result']['state'];
        switch ($state) {
            case TransactionForCheck::TRANSACTION_PAID:
                PaymentService::payListener(null, $transaction, 'after-pay');
                break;
            case TransactionForCheck::TRANSACTION_CANCELLED:
                PaymentService::payListener(null, $transaction, 'cancel-pay');
                break;
            case !TransactionForCheck::STATUS_IN_CHECK:
                $transaction->update(['status' => $state]);
                break;
        }
        return true;
    }
}
