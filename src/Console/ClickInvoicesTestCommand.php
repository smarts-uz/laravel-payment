<?php

namespace Teamprodev\LaravelPayment\Console;

use Illuminate\Console\Command;
use Teamprodev\LaravelPayment\Models\Transaction;
use Teamprodev\LaravelPayment\Services\InvoiceService;

class ClickInvoicesTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'click:test {phone}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transaction = Transaction::query()->create([
            'transactionable_id' => 1,
            'transactionable_type' => "App\Models\ModelName",
            'system_transaction_id' => 1,
            'currency_code' => 860,
            'payment_system' => 'click',
            'amount' => 4000,
            'state' => Transaction::STATE_CREATED
        ]);

        InvoiceService::createInvoice($transaction, $this->argument('phone'));
    }
}
