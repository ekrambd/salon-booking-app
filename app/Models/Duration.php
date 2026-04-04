<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    use HasFactory;

    protected $table = 'durations';
    protected $fillable = [
        'time_duration',
        'time_unit',
        'status',
    ];
    public static function rules($id = null)
    {
        $rules = [
            'time_duration' => 'required|string|min:1|max:60',
            'time_unit' => 'required|string|in:Minutes,Hours',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function durationStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:durations,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }

}
