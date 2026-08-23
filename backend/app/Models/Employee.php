<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['store_id', 'name', 'position'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }
}