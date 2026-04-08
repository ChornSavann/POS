<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model
{
    protected $fillable = [
        'image', 'name', 'barcode', 'category_id',
        'brand_id', 'unit_id', 'cost', 'price',
        'alert_qty', 'status','sale_unit_id','purchase_unit_id'
    ];

    // ទំនាក់ទំនងទៅកាន់ Unit
    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_id');
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }

    // ក្នុងឯកសារ app/Models/Product.php

    public function saleUnit()
    {
        return $this->belongsTo(Units::class, 'sale_unit_id');
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Units::class, 'purchase_unit_id');
    }

    // public function stock()
    // {
    //     return $this->hasOne(Stock::class);
    // }
    public function stock()
    {
        return $this->hasOne(Stock::class)->withDefault([
            'qty' => 0,   // fallback qty
            'note' => 'Initial Stock'
        ]);
    }

    public function orderItems(): HasMany
    {
        // សូមពិនិត្យមើលឈ្មោះ Model របស់បង (អាចជា OrderDetail ឬ OrderItem)
        // និង Foreign Key (product_id)
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function stocks(): HasMany
    {
        // ប្រាកដថាបងមាន Model ឈ្មោះ Stock
        // 'product_id' គឺជា Foreign Key នៅក្នុង table stocks
        return $this->hasMany(Stock::class, 'product_id');
    }
}
