<?php

namespace Teamprodev\LaravelPayment\Services;


class PaymentService
{
    /*
    *   return string
    */
    public static function convertModelToKey($model)
    {
        return require base_path('/app/Http/Payments/model_key.php');
    }
    /*
    * $key - key of model
    * returns model or null
    *
    */
    public static function convertKeyToModel($key)
    {
        return require base_path('/app/Http/Payments/key_model.php');
    }
    /*
    * returns true/false
    */
    public static function isProperModelAndAmount($model, $amount)
    {
        return require base_path('/app/Http/Payments/is_proper.php');
    }

    /*
    * $model - Payable model
    * $amount - amount for pay
    * $action_type - type of action: before-pay, paying, after-pay, cancelled
    */
    public static function payListener($model, $transaction, $action_type)
    {
        switch ($action_type) {
            case 'before-pay':
                require base_path('/app/Http/Payments/before_pay.php');
                break;

            case 'paying':
                require base_path('/app/Http/Payments/paying.php');
                break;

            case 'after-pay':
                require base_path('/app/Http/Payments/after_pay.php');
                break;

            case 'cancel-pay':
                require base_path('/app/Http/Payments/cancel_pay.php');
                break;

            case 'after-paid-invoice':
                require base_path('/app/Http/Payments/after_paid_invoice.php');
                break;

            case 'after-cancelled-invoice':
                require base_path('/app/Http/Payments/after_cancelled_invoice.php');
                break;
        }
    }
}
