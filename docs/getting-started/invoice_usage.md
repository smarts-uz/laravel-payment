# Invoice Usage

If you want to integrate with invoices (receipts), you can easily use our service functions.

Available payment drivers:

* click (invoices)
* payme (receipts)

It allows you to send invoices to users.  

For that you should create transaction and pass it to function with user's number

```php
use Teamprodev\LaravelPayment\Services\InvoiceService;

const STATUS_NEW = 0;

$transaction = Transaction::query()->create([
    'transactionable_id' => $sale->id,
    'transactionable_type' => Sale::class,
    'system_transaction_id' => 1,
    'currency_code' => 860,
    'payment_system' => $method,
    'amount' => $amount,
    'state' => STATUS_NEW
]);

switch ($method){
    case 'payme' :
        InvoiceService::receiptsSend($transaction, $phone);
        break;
    case 'click' :
        InvoiceService::createInvoice($transaction, $phone);
        break;
}

// WARNING!
// It is required if you send receipt(with payme) to user.
// Because you should check transaction status by payme
// On click it is not required! 
PaymentTransaction::query()->create([
    'payment_id' => $payment->id,
    'transaction_id' => $transaction->id
]);
```

## !!!     Warning     !!!  

**It is required if you send receipt(with payme) to user.**  

You should check payme receipts status. You can do that easily by running this console command. 

```bash
php artisan check:status
```

!!!     Warning     !!!

You should run this command in every some time. For example, each 60 seconds
