<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    use HasFactory;

    protected $table = 'working_days';
    protected $fillable = [
        'name',
        'sort_order',
        'status',
    ];
    public static function rules($id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:working_days,name,' . $id,
            'sort_order' => 'required|integer|in:1,2,3,4,5,6,7|unique:working_days,sort_order,' . $id,
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function workingDayStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:working_days,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
}
