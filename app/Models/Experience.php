<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $table = 'experiences';
    protected $fillable = [
        'year_of_exp',
        'status',
    ];
    public static function rules($id = null)
    {
        $rules = [
            'year_of_exp' => 'required|string|max:20|unique:experiences,year_of_exp,' . $id,
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function experienceStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:experiences,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
}
