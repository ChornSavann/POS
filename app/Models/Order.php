<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // កំណត់ឈ្មោះ Field ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យ (Mass Assignment)
    protected $fillable = [
        'order_date', 'invoice_no', 'table_id', 'customer_id',
        'sub_total', 'discount', 'total_discount', 'tax', 'grand_total',
        'is_credit', 'note', 'seller_id', 'store_id','is_completed',
        'is_paid','debt_amount'
    ];

    // កំណត់ប្រភេទទិន្នន័យ (Casting)
   protected $casts = [
        'order_date'   => 'datetime',
        'is_credit'    => 'boolean',
        'is_completed' => 'boolean',
        'is_paid'      => 'boolean',
        'sub_total'    => 'decimal:2',
        'grand_total'  => 'decimal:2',
        'tax'          => 'decimal:2',
        'total_discount' => 'decimal:2',
    ];

    /* --- Relationships --- */

    public function table(): BelongsTo {
        return $this->belongsTo(Tables::class, 'table_id');
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function seller(): BelongsTo {
        return $this->belongsTo(User::class, 'seller_id'); // ក្នុង Laravel ជាទូទៅប្រើ User Model
    }

    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function payments(): HasMany {
        return $this->hasMany(OrderPayment::class,'order_id');
    }

    public function bank():HasMany{
        return $this->hasMany(Bank::class,'bank_id');
    }

    // App\Models\Order.php

    


}
