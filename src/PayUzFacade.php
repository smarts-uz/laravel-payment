<?php

namespace Teamprodev\LaravelPayment;

use Illuminate\Support\Facades\Facade;


class PayUzFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pay-uz';
    }
}
