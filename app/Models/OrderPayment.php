<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_date',
        'payment_method',
        'paid_dollar',
        'paid_riel',
        'exchange_rate',
        'paid_amount',
        'balance_after',
        'payment_status',
        'payment_ref',
        // 'debt_amount',
        'note'
    ];

    protected $casts = [
        'payment_date'  => 'datetime',
        'paid_dollar'   => 'decimal:2',
        'paid_riel'     => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'paid_amount'   => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * ភ្ជាប់ទំនាក់ទំនងទៅកាន់ Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    
    public function bank()
    {
        // កំណត់ថា payment_method គឺជា Foreign Key ទៅកាន់ Table Banks
        return $this->belongsTo(Bank::class, 'payment_method');
    }
}
