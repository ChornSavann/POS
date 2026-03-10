<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'currency',
        'note',
        'is_active',
    ];

    // បង្កើត Accessor ដើម្បីបង្ហាញឈ្មោះធនាគារ និងលេខគណនីបញ្ចូលគ្នា
    public function getFullDetailsAttribute()
    {
        return "{$this->bank_name} - {$this->account_number} ({$this->account_name})";
    }
}
