<?php

namespace Teamprodev\LaravelPayment\Services;

use Illuminate\Support\Facades\Http;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Models\Transaction;
use Teamprodev\LaravelPayment\Models\TransactionForCheck;

/**
 * Work with payme, click invoices - e.g create, send, check invoices
 *
 */
class InvoiceService
{
    const CHECKOUT_PAYME_URL = "https://checkout.paycom.uz/api";
    const CREATE_INVOICE_URL = "https://api.click.uz/v2/merchant/invoice/create/";
    const CHECK_INVOICE_URL = "https://api.click.uz/v2/merchant/invoice/status/";

    /**
     * Create payme receipt for send to user
     *
     * @param $transaction
     * @return array|mixed
     */
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


    /**
     * Send payme receipt to user by system_transaction_id
     *
     * @param $transaction
     * @param $phone
     * @return array|mixed
     */
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

    /**
     * Check payme receipt for paid
     *
     * @param $transaction
     * @return array|mixed
     */
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

    /**
     * Cancel payme receipt
     *
     * @param $transaction
     * @return array|mixed
     */
    public static function receiptCancel($transaction)
    {
        return Http::withHeaders([
            'X-Auth' => self::generatePaymeHeader(),
        ])->post(self::CHECKOUT_PAYME_URL, [
            "id" => $transaction->id,
            "method" => "receipts.cancel",
            "params" => [
                "id" => $transaction->system_transaction_id
            ]
        ])->json();
    }

    /**
     * Generate payne header for use on receipt functions
     *
     * @return string
     */
    public static function generatePaymeHeader()
    {
        $params = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::PAYME);
        return $params['merchant_id'] . ':' . $params['password'];
    }

    /**
     * Create click invoice for send to user
     *
     * @param $amount
     * @param $phone_number
     * @param $transaction
     * @return void
     */
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
            'state' => Transaction::STATE_CREATED
        ]);
    }

    /**
     * Check click invoice
     *
     * @param $invoice_id
     * @return array|mixed
     */
    public static function checkInvoiceStatus($invoice_id)
    {
        $service_id = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK)['service_id'];
        return Http::withHeaders([
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            'Auth' => self::generateHeader(),
        ])->get(self::CHECK_INVOICE_URL . $service_id . '/' . $invoice_id)->json();
    }

    /**
     * Generate payme header for use on invoice functions
     *
     * @return array|mixed
     */
    public static function generateHeader(): string
    {
        $params = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK);
        $digest = sha1(now()->timestamp . $params['secret_key']);
        return $params['merchant_user_id'] . ":" . $digest . ":" . now()->timestamp;
    }
}
