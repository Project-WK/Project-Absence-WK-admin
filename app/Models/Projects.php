<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_code', 'name', 'client_name', 'description',
        'address', 'location_id',
        'project_value', 'payment_status', 'status',
        'start_date', 'end_date'
    ];

    protected $casts = [
        'project_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
