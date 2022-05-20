<?php

namespace Teamprodev\LaravelPayment;

use Teamprodev\LaravelPayment\Models\Transaction;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Http\Classes\Payme\Payme;
use Teamprodev\LaravelPayment\Http\Classes\Click\Click;
use Teamprodev\LaravelPayment\Http\Classes\Paynet\Paynet;
use Teamprodev\LaravelPayment\Http\Classes\PaymentException;

class PayUz
{

    protected $driverClass = null;

    /**
     * PayUz constructor.
     */
    public function __construct()
    {
    }


    /**
     * Select payment driver
     * @param null $driver
     * @return $this
     */
    public function driver($driver = null)
    {
        switch ($driver) {
            case PaymentSystem::PAYME:
                $this->driverClass = new Payme;
                break;
            case PaymentSystem::CLICK:
                $this->driverClass = new Click;
                break;
            case PaymentSystem::PAYNET:
                $this->driverClass = new Paynet;
                break;
        }
        return $this;
    }


    public function redirect($model, $amount, $currency_code = Transaction::CURRENCY_CODE_UZS, $url = null)
    {
        $this->validateDriver();
        $driver = $this->driverClass;
        $params = $driver->getRedirectParams($model, $amount, $currency_code, $url);
        $view = 'pay-uz::merchant.index';
        if (!empty($driver::CUSTOM_FORM))
            $view = $driver::CUSTOM_FORM;
        echo view($view, compact('params'));
    }


    public function handle()
    {
        $this->validateDriver();
        try {
            return $this->driverClass->run();
        } catch (PaymentException $e) {
            return $e->response();
        }

        return $this;
    }


    /**
     * @throws \Exception
     */
    public function validateModel($model, $amount, $currency_code)
    {
        if (is_null($model))
            throw new \Exception('Modal can\'t be null');
        if (is_null($amount) || $amount == 0)
            throw new \Exception('Amount can\'t be null or 0');
        if (is_null($currency_code))
            throw new \Exception('Currency code can\'t be null');
    }

    /**
     * @throws \Exception
     */
    public function validateDriver()
    {
        if (is_null($this->driverClass))
            throw new \Exception('Driver not selected');
    }
    public function setDescription($hasDescription)
    {
        $this->driverClass->setDescription($hasDescription);
        return $this;
    }
}
