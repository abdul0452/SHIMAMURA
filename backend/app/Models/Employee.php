<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['store_id', 'name', 'position', 'employee_number', 'date_of_joining'];

    protected $casts = [
        'date_of_joining' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}