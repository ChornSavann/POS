<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    use HasFactory;

    // ប្រាប់ Model ឱ្យប្រើតារាងឈ្មោះ 'tables'
    protected $table = 'tables';

    protected $fillable = [
        'name',
        'note',
        'status'
    ];
}
