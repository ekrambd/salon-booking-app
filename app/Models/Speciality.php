<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    use HasFactory;
    protected $table = 'specialities';
    protected $fillable = ['name', 'slug', 'status'];

    public static function rules($id = null)
    {
        $rules = [
            'name' => 'required|string|max:191|unique:specialities,name,' . $id,
            'slug' => 'nullable|string|max:191',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function specialityStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:specialities,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
}
