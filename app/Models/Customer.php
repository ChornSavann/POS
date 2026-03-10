<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';
    protected $fillable = [
        'name',
        'customer_code',
        'zone',
        'email',
        'phone',
        'address',
        'status',
    ];

    /**
     * មុខងារសម្រាប់បង្ហាញ Zone ឱ្យមានពណ៌សម្គាល់ (Optional)
     * ឧទាហរណ៍៖ Zone A ពណ៌ខៀវ, Zone B ពណ៌បៃតង
     */
    public function getZoneBadgeAttribute()
    {
        if (!$this->zone) return '<span class="badge bg-light text-dark border">No Zone</span>';
        
        $colors = [
            'A' => 'bg-primary',
            'B' => 'bg-success',
            'C' => 'bg-warning text-dark',
            'D' => 'bg-info text-white',
        ];

        $class = $colors[strtoupper($this->zone)] ?? 'bg-secondary';
        return '<span class="badge ' . $class . '">' . $this->zone . '</span>';
    }
    // បង្កើត Label សម្រាប់បង្ហាញក្នុង Blade
public function getStatusLabelAttribute()
{
    if ($this->status == 1) {
        return '<span class="badge bg-success shadow-sm"><i class="bi bi-check-circle me-1"></i> Active</span>';
    }
    return '<span class="badge bg-danger shadow-sm"><i class="bi bi-x-circle me-1"></i> Inactive</span>';
}
}