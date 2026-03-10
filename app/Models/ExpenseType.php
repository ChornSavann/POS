<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;
    protected $table = 'expense_types';

    protected $fillable = [
        'code',
        'amount',
        'expens_id', // ភ្ជាប់ទៅ ItemExpens
        'bank_id',   // ភ្ជាប់ទៅ Bank
        'status',
    ];

    /**
     * ការភ្ជាប់ទៅកាន់ Item Expense (ប្រភេទចំណាយមេ)
     */
    public function itemExpense()
    {
        // សន្មតថាបងមាន Model ឈ្មោះ ItemExpens
        return $this->belongsTo(ItemExpens::class, 'expens_id');
    }

    /**
     * ការភ្ជាប់ទៅកាន់ Bank (ធនាគារដែលចំណាយចេញ)
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    // នៅក្នុង Model ExpenseType.php
    public function getStatusLabelAttribute()
    {
        if ($this->status == 'active') {
            return '<span class="badge badge-active">សកម្ម</span>';
        }
        return '<span class="badge bg-danger bg-opacity-10 text-danger">មិនសកម្ម</span>';
    }
}
