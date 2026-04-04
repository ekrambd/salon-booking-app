<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffService extends Model
{
    use HasFactory;

    protected $table = 'staff_services';

    protected $fillable = [
        'user_id',
        'staff_id',
        'service_id',
        'duration_id',
        'price',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function duration()
    {
        return $this->belongsTo(Duration::class);
    }
}
