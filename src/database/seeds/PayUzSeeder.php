<?php

namespace Teamprodev\LaravelPayment\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Teamprodev\LaravelPayment\Models\PaymentSystem;
use Teamprodev\LaravelPayment\Models\PaymentSystemParam;

class PayUzSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('payment_systems')) {
            PaymentSystem::query()->firstOrCreate([
                'name'      => 'Payme',
                'system'    => 'payme'
            ]);
            PaymentSystem::query()->firstOrCreate([
                'name'      => 'Click',
                'system'    => 'click'
            ]);
            PaymentSystem::query()->firstOrCreate([
                'name'      => 'Paynet',
                'system'    => 'paynet'
            ]);
            PaymentSystem::query()->firstOrCreate([
                'name'      => 'Stripe',
                'system'    => 'stripe'
            ]);
        }
        if (Schema::hasTable('payment_system_params')) {
            //Paycom
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'payme',
                'label'     => 'Login',
                'name'      => 'login',
                'value'     => 'Paycom'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'payme',
                'label'     => 'Merchant id',
                'name'      => 'merchant_id',
                'value'     => 'merchant'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'payme',
                'label'     => 'Password',
                'name'      => 'password',
                'value'     => 'password'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'payme',
                'label'     => 'Key',
                'name'      => 'key',
                'value'     => 'key'
            ]);
            //Click
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'click',
                'label'     => 'Service id',
                'name'      => 'service_id',
                'value'     => 'service_id'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'click',
                'label'     => 'Secret key',
                'name'      => 'secret_key',
                'value'     => 'key'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'click',
                'label'     => 'Merchant Id',
                'name'      => 'merchant_id',
                'value'     => '0000'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'click',
                'label'     => 'Merchant user id',
                'name'      => 'merchant_user_id',
                'value'     => '0000'
            ]);

            //Paynet
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'paynet',
                'label'     => 'Login',
                'name'      => 'login',
                'value'     => 'login'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'paynet',
                'label'     => 'Password',
                'name'      => 'password',
                'value'     => 'password'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'paynet',
                'label'     => 'Service id',
                'name'      => 'service_id',
                'value'     => 'service_id'
            ]);

            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'stripe',
                'label'     => 'Secret key',
                'name'      => 'secret_key',
                'value'     => 'secret_key'
            ]);

            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'stripe',
                'label'     => 'Publishable key',
                'name'      => 'publishable_key',
                'value'     => 'publishable_key'
            ]);
            PaymentSystemParam::query()->firstOrCreate([
                'system'    => 'stripe',
                'label'     => 'Proxy',
                'name'      => 'proxy',
                'value'     => ''
            ]);
        }
    }
}
