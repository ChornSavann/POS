<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;

class Brand extends Model
{
    use HasFactory;

    // កំណត់ Column ដែលអាចបញ្ចូលទិន្នន័យបាន
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
    ];

    /**
     * ប្រសិនបើ Brand មួយមាន Product ច្រើន (Relationship)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
