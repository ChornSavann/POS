<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    // កំណត់ឈ្មោះ Table ឱ្យច្បាស់លាស់ (ការពារក្រែង Laravel រកមិនឃើញ)
    protected $table = 'purchase_items';

    // កំណត់ Column ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យ (Mass Assignment)
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unit_cost',
        'unit_price',
        'discount',
        'subtotal',
    ];

    /**
     * Relationship ទៅកាន់ Purchase (មេ)
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Relationship ទៅកាន់ Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
