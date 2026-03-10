<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory;
    protected $table = 'seller';
    protected $fillable = [
        'name',
        'gender',
        'phone',
        'email',
        'address',
        'status',
    ];

    // បើចង់ឱ្យ Status បង្ហាញជាអក្សរស្អាតៗពេលហៅប្រើ
    public function getStatusLabelAttribute()
    {
        return $this->status ? 'សកម្ម (Active)' : 'អសកម្ម (Inactive)';
    }
}