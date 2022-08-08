<?php

namespace Teamprodev\LaravelPayment\Console;

use Illuminate\Console\Command;
use Teamprodev\LaravelPayment\Models\TransactionForCheck;

class CheckInvoiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payme invoices for pay';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var TransactionForCheck $transactionsForCheck */
        $transactionsForCheck = TransactionForCheck::with('transaction')
            ->where('status', TransactionForCheck::STATUS_WAIT_FOR_CHECK)
            ->get();

        foreach ($transactionsForCheck as $transactionForCheck) {
            $transactionForCheck->check();
        }

        return 0;
    }
}
