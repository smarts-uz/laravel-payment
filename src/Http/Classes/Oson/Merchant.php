<?php

namespace Teamprodev\LaravelPayment\Http\Classes\Oson;


class Merchant
{
    public $config;
    public $acc;
    public function __construct($config,$acc)
    {
        $this->acc = $acc;
        $this->config = $config;
    }

    /**
     * @throws OsonException
     */
    public function checkAuth(){
        if ($this->config['login'] != $this->acc['login'] || $this->config['password'] != $this->acc['password'] )
            throw new OsonException(OsonException::ERROR_AUTHORIZATION,[]);
    }
}
