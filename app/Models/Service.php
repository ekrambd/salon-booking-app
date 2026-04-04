<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = [
        'name',
        'status',
    ];
    public static function rules($id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function serviceStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:services,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
}
