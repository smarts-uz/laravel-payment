<?php

namespace Teamprodev\LaravelPayment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Teamprodev\LaravelPayment\Services\InvoiceService;
use Teamprodev\LaravelPayment\Services\PaymentService;

/**
 * @package App\Models
 * @property int $id
 * @property int $status
 * @property string $transaction_id
 * @property Transaction $transaction
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionForCheck extends Model
{
    use HasFactory;

    const STATUS_IN_CHECK = 0;

    const TRANSACTION_PAID = 4;
    const TRANSACTION_CANCELLED = 50;

    protected $table = 'transactions_for_check';

    protected $fillable = [
        'transaction_id',
        'status'
    ];

    /**
     * Transaction that has to be checked
     *
     * @return BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    /**
     * Check waiting transactions status from payme
     * Change status according to payme response status
     *
     * @return bool
     */
    public function check(): bool
    {
        $transaction = $this->transaction;
        if (now()->diffInMinutes($this->created_at) > config('payuz')['invoice_life_time']) {
            InvoiceService::receiptCancel($transaction);
        }

        $response = InvoiceService::receiptCheck($transaction);
        $state = $response['result']['state'];
        if ($state == self::TRANSACTION_PAID) {
            PaymentService::payListener(null, $transaction, 'after-paid-invoice');
        } elseif ($state == self::TRANSACTION_CANCELLED) {
            PaymentService::payListener(null, $transaction, 'after-cancelled-invoice');
        } elseif ($state != self::STATUS_IN_CHECK) {
            $transaction->update(['status' => $state]);
        }
        return true;
    }
}
