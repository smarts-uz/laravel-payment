
# Payment Systems


------
- Payme
- Click
- Paynet


## Installation

You can install the package via composer:

```bash
composer require teamprodev/laravel-payment
```
Publishing required files of package:

```bash
php artisan vendor:publish --provider="Teamprodev\LaravelPayment\PayUzServiceProvider"
```
Migrate tables:

```bash
php artisan migrate
```

Seed settings:

```bash
php artisan db:seed --class="Teamprodev\LaravelPayment\database\seeds\PayUzSeeder"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
