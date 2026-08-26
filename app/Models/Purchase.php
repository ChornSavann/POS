<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = [
        'reference_no',
        'purchase_date',
        'supplier_id',
        'store_id',
        'seller_id',
        'status',
        'user_id',
        'note',
        'grand_total'
    ];

    // ទាញយក Items ទាំងអស់របស់ការទិញនេះ (One-to-Many)
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Stores::class,'store_id');
    }
    // app/Models/Purchase.php
    public function user()
    {
        // ប្រាប់ Laravel ថា seller_id គឺសំដៅលើ ID ក្នុងតារាង Users
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function user_id()
    {
        // ប្រាប់ Laravel ថា seller_id គឺសំដៅលើ ID ក្នុងតារាង Users
        return $this->belongsTo(User::class, 'user_id');
    }
}
