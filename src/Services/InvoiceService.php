<?php

namespace Teamprodev\LaravelPayment\Services;

use Illuminate\Support\Facades\Http;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Models\Transaction;
use Teamprodev\LaravelPayment\Models\TransactionForCheck;

class InvoiceService
{
    const CHECKOUT_PAYME_URL = "https://checkout.paycom.uz/api";
    const CREATE_INVOICE_URL = "https://api.click.uz/v2/merchant/invoice/create/";
    const CHECK_INVOICE_URL = "https://api.click.uz/v2/merchant/invoice/status/";

    public static function receiptsCreate($transaction)
    {
        $response = Http::withHeaders([
            'X-Auth' => self::generatePaymeHeader(),
        ])->post(self::CHECKOUT_PAYME_URL, [
            "id" => $transaction->id,
            "method" => "receipts.create",
            "params" => [
                "amount" => $transaction->amount * 100,
                "account" => [
                    "order_id" => $transaction->transactionable_id
                ]
            ],
        ])->json();
        $transaction->state = Transaction::STATE_CREATED;
        $transaction->system_transaction_id = $response['result']['receipt']['_id'];
        $transaction->save();
        return $response;
    }

    public static function receiptsSend($transaction, $phone)
    {
        static::receiptsCreate($transaction);
        $response =  Http::withHeaders([
            'X-Auth' => self::generatePaymeHeader(),
        ])->post(self::CHECKOUT_PAYME_URL, [
            "id" => $transaction->id,
            "method" => "receipts.send",
            "params" =>[
                "id" => $transaction->system_transaction_id,
                "phone" => $phone
            ]
        ])->json();
        $transaction->state = Transaction::STATE_CREATED;
        $transaction->save();

        TransactionForCheck::query()->create([
            'transaction_id' => $transaction->id
        ]);

        return $response;
    }

    public static function receiptCheck($transaction)
    {
        return Http::withHeaders([
            'X-Auth' => self::generatePaymeHeader(),
        ])->post(self::CHECKOUT_PAYME_URL, [
            "id" => $transaction->id,
            "method" => "receipts.check",
            "params" => [
                "id" => $transaction->system_transaction_id
            ]
        ])->json();
    }

    public static function receiptCancel($transaction)
    {
        return Http::withHeaders([
            'X-Auth' => config('payments.payme.merchant_id') . ':' . config('payments.payme.key'),
        ])->post(self::CHECKOUT_PAYME_URL, [
            "id" => $transaction->id,
            "method" => "receipts.cancel",
            "params" => [
                "id" => $transaction->system_transaction_id
            ]
        ])->json();
    }

    public static function generatePaymeHeader()
    {
        $params = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::PAYME);
        return $params['merchant_id'] . ':' . $params['key'];
    }

    public static function createInvoice($amount, $phone_number, $transaction)
    {
        $service_id = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK)['service_id'];
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            'Auth' => self::generateHeader(),
        ])->post(self::CREATE_INVOICE_URL, [
            "service_id" => $service_id,
            "amount" => $amount,
            "phone_number" => $phone_number,
            "merchant_trans_id" => $transaction->transactionable_id
        ])->json();

        $transaction->update([
            'system_transaction_id' => $response['invoice_id'],
            'status' => Transaction::STATE_INVOICE_SENT
        ]);
    }

    public static function checkInvoiceStatus($invoice_id)
    {
        $service_id = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK)['service_id'];
        return Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            'Auth' => self::generateHeader(),
        ])->get(self::CHECK_INVOICE_URL . $service_id . '/' . $invoice_id)->json();
    }

    public static function generateHeader(): string
    {
        $digest = sha1(now()->timestamp . config('payments.click.secret_key'));
        return config('payments.click.merchant_user_id') . ":" . $digest . ":" . now()->timestamp;
    }
}
