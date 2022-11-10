# Installation

Voyager is super easy to install. After creating your new Laravel application you can include the Voyager package with the following command:

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

The MIT License (MIT). Please see [License File](../LICENSE.md) for more information.

