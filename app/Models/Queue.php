<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'patient_name',
        'patient_nric',
        'patient_phone',
        'patient_address',
        'department_id',
        'status',
        'position'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
