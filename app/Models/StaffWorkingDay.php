<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffWorkingDay extends Model
{
    use HasFactory;

    protected $table = 'staff_working_days';
    protected $fillable = [
        'user_id',
        'staff_id',
        'working_day_id',
    ];
    public function staffs()
    {
        return $this->belongsToMany(
            Staff::class,
            'staff_working_days'
        );
    }
}
