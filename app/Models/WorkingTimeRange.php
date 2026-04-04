<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingTimeRange extends Model
{
    use HasFactory;
    protected $table = 'working_time_ranges';
    protected $fillable = [
        'title',
        'from_time',
        'to_time',
        'status',
    ];
    public static function rules($id = null)
    {
        $rules = [
            'title' => 'required|string|max:255|unique:working_time_ranges,title,' . $id,
            'from_time' => 'required',
            'to_time' => 'required|after:from_time',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function workingTimeRangesStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:working_time_ranges,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }

}
