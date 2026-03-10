<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $fillable = ['name', 'baseunit_id', 'operator', 'operator_value', 'note'];

    // ទាញរកខ្នាតមេ
    public function baseUnit()
    {
        return $this->belongsTo(Units::class, 'baseunit_id');
    }

    // ទាញរកខ្នាតកូនៗ
    public function childUnits()
    {
        return $this->hasMany(Units::class, 'baseunit_id');
    }
}
