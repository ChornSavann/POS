<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stores extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',    // <--- ត្រូវតែមានឈ្មោះ Column នេះ ទើបវាព្រម Save ចូល DB
        'logo',
        'phone',
        'email',
        'address',
        'status',
    ];
}
