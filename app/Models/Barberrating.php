<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barberrating extends Model
{
    use HasFactory;

    public function staff()
    {
    	return $this->belongsTo(Staff::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
