<?php

namespace Teamprodev\LaravelPayment\Http\Controllers;

use App\Http\Controllers\Controller;
use Teamprodev\LaravelPayment\Http\Requests\ClientCheckoutRequest;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Services\PaymentSystemService;

class ClientController extends Controller
{
    public function checkout(ClientCheckoutRequest $request)
    {
        $data = $request->validated();
        $amount = $data['amount'];
        $transaction_param = $data['key'];
        switch ($data['payment_system']) {
            case PaymentSystem::CLICK:
                $params = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK);
                $service_id = $params['service_id'];
                $merchant_id = $params['merchant_id'];
                $return_url = $params['return_url'];
                return redirect()->to("https://my.click.uz/services/pay?service_id=$service_id&merchant_id=$merchant_id&amount=$amount.00&transaction_param=$transaction_param&return_url=$return_url");

            case PaymentSystem::PAYME:
                $params = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::PAYME);
                $merchant_id = $params['merchant_id'];
                $amount *= 100;
                $data = "m=$merchant_id;ac.user_id=$transaction_param;a=$amount";
                return redirect()->to('https://checkout.paycom.uz/' . base64_encode($data));

            default:
                return abort(400);
        }
    }
}
