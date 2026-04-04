<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'latitude',
        'longitude',
        'status',
    ];

    public static function rules($id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:branches,name,' . $id,
            'address' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:branches,email,' . $id,
            'phone' => 'required|string|max:20|unique:branches,phone,' . $id,
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function branchStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:branches,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
}
