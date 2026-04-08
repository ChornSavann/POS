<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'qty', 'price', 'discount', 'total'
    ];

    /* --- Relationships --- */

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    // public function product(): BelongsTo {
    //     return $this->belongsTo(Product::class);
    // }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
