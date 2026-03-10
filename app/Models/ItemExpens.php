<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemExpens extends Model
{
    protected $table = 'item_expens';

    protected $fillable = [
        'code',
        'name',
        'status'
    ];
}
