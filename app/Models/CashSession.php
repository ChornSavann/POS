<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashSession extends Model
{
    use HasFactory;

    // ១. កំណត់ឈ្មោះ Table (ប្រសិនបើបងដាក់ឈ្មោះប្លែកពីបព្បញ្ញត្តិ Laravel)
    protected $table = 'cash_sessions';

    // ២. កំណត់ Field ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបាន (Mass Assignment)
    protected $fillable = [
        'user_id',
        'opening_time',
        'closing_time',
        'opening_balance',
        'system_cash',
        'system_bank',
        'system_discount',
        'actual_cash',
        'difference',
        'note',
        'status'
    ];

    // ៣. កំណត់ប្រភេទទិន្នន័យ (Casting) ដើម្បីឱ្យស្រួលប្រើប្រាស់
    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'opening_balance' => 'decimal:2',
        'system_cash' => 'decimal:2',
        'system_bank' => 'decimal:2',
        'system_discount' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    /**
     * Relationship: Session នេះជារបស់ User ណា?
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: ជួយឱ្យយើងងាយស្រួលឆែករក Session ដែលកំពុងបើក
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Function បន្ថែម៖ គណនាផលសងដោយស្វ័យប្រវត្តិ
     * លុយដែលត្រូវមាន = (លុយដើមគ្រា + លុយលក់បានជាសាច់ប្រាក់)
     */
    public function calculateExpectedCash()
    {
        return $this->opening_balance + $this->system_cash;
    }
}
