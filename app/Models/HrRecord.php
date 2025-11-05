<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrRecord extends Model
{
    use HasFactory;

    // Allow these fields for mass assignment
    protected $fillable = [
        'date',
        'number_of_employees',
        'total_work_hours',
    ];
}
