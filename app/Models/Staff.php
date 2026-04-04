<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'branch_id',
        'specialty_id',
        'experience_id',
        'working_day_id',
        'working_time_range_id',
        'slot_duration_minutes',
        'balance',
        'current_status',
        'created_by',
        'updated_by',
    ];

    public static function rules($id = null)
    {
        $rules = [
            'name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:191|unique:users,phone,' . $id,
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password',
            'status' => 'nullable|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function updateRules($id = null)
    {
        $rules = [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function storeServiceRules()
    {
        $rules = [
            'branch_id' => 'required|exists:branches,id',
            'specialty_id' => 'required|exists:specialities,id',
            'experience_id' => 'required|exists:experiences,id',
            'working_time_range_id' => 'required|exists:working_time_ranges,id',

            'working_day_ids' => 'required|array|min:1',
            'working_day_ids.*' => 'exists:working_days,id',

            'service_id' => 'required|array|min:1',
            'service_id.*' => 'required|exists:services,id',

            'duration_id' => 'required|array|min:1',
            'duration_id.*' => 'required|exists:durations,id',

            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
        ];

        return $rules;
    }
    public static function staffStausUpdateRules()
    {
        $rules = [
            'id' => 'required|string|exists:users,id',
            'status' => 'required|in:Active,Inactive',
        ];

        return $rules;
    }
    public static function spLoginRules()
    {
        $rules = [
            'login' => 'required|string',
            'password' => 'required|string|min:6',
        ];

        return $rules;
    }
    public static function apiStoreServiceRules()
    {
        $rules = [
            'branch_id' => 'required|exists:branches,id',
            'specialty_id' => 'required|exists:specialities,id',
            'experience_id' => 'required|exists:experiences,id',
            'working_time_range_id' => 'required|exists:working_time_ranges,id',

            'working_day_ids' => 'required|array|min:1',
            'working_day_ids.*' => 'exists:working_days,id',

            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',

            'services.*duration_id' => 'required|exists:durations,id',

            'services.*.price' => 'required|numeric|min:1',
        ];

        return $rules;
    }

    public function workingDays()
    {
        return $this->belongsToMany(
            WorkingDay::class,
            'staff_working_days'
        );
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function specialty()
    {
        return $this->belongsTo(Speciality::class);
    }
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
    public function workingTimeRange()
    {
        return $this->belongsTo(WorkingTimeRange::class);
    }
    public function services()
    {
        return $this->hasMany(
            StaffService::class
        );
    }
}
