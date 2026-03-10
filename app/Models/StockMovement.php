<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',       // IN / OUT
        'qty',
        'reference',  // purchase_no / sale invoice
        'note'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
