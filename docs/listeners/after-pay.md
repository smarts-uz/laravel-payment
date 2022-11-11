# After pay

With the installation of Package you will find a new configuration files located at `config/payuz.php`.  
In this file you can find various options to change the configuration of your Package installation.

**If you cache your configuration files please make sure to run `php artisan config:clear` after you changed something.**

Below we will take a deep dive into the configuration file and give a detailed description of each configuration set.

## Payuz

```php
<?php

/*
 * You can place your custom package configuration in here.
 */
return [

    // Assets folder published folder name.

    'pay_assets_path' => '/vendor/pay-uz',
    'control_panel' => [
        'middleware' => null
    ],
    'multi_transaction' => true,

    'invoice_life_time' => 15, // minutes
];
```
