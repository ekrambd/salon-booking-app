<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Speciality;
use App\Models\Staff;
use App\Models\StaffService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\WorkingDay;
use App\Models\WorkingTimeRange;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\RateLimiter;
use Auth;
class BaseController extends Controller
{
    public function specialities(Request $request)
    {
    	try
    	{
    		$query = Speciality::query();

    		if ($request->has('search') && !empty($request->search)) {
	            $search = $request->search;
	            $query->where('name', 'LIKE', "%{$search}%");
	        }

    		if ($request->is_paginate == 1) {

	            $per_page = $request->per_page ?? 10;

	            $data = $query->latest()->paginate($per_page);

	        } else {

	            $data = $query->latest()->get();
	        }
	        return response()->json($data);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function services(Request $request)
    {
    	try
    	{
    		$query = Service::query();

    		if ($request->has('search') && !empty($request->search)) {
	            $search = $request->search;
	            $query->where('name', 'LIKE', "%{$search}%");
	        }

    		if ($request->is_paginate == 1) {

	            $per_page = $request->per_page ?? 10;

	            $data = $query->latest()->paginate($per_page);

	        } else {

	            $data = $query->latest()->get();
	        }
	        return response()->json([
	            'status' => true,
	            'data'   => $data
	        ]);

    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function barberSignup(Request $request)
    {
    	$validator = Validator::make($request->all(), [

	        'name' => 'required|string|max:255',

	        'email' => 'nullable|email|unique:users,email',

	        'phone' => 'required|unique:users,phone',

	        'password' => 'required|min:6|same:confirm_password',

	        'confirm_password' => 'required',

	        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

	        // 'specialities' => 'required|array',

	        // 'specialities.*' => 'exists:specialities,id',

	        'services' => 'required|array',

	        //'services.*' => 'exists:services,id',

	        'working_day_ids' => 'required|array',
	        'working_day_ids.*' => 'exists:working_days,id',

	        'slot_duration' => 'required|numeric',

	        'working_time_range_id' => 'required|integer|exists:working_time_ranges,id',

	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'errors' => $validator->errors()
	        ], 422);
	    }

	    DB::beginTransaction();

	    try {

	        $imageName = null;

	        $count = User::count();
	        $count+=1;

	        if ($request->hasFile('image')) {
	            $image = $request->file('image');
	            $imageName = time().$count.'_'.$image->getClientOriginalName();
	            $image->move(public_path('uploads/users'), $imageName);
	        }

	        $user = User::create([
	            'name' => $request->name,
	            'email' => $request->email,
	            'phone' => $request->phone,
	            'password' => Hash::make($request->password),
	            'image' => $imageName,
	            'user_type_id' => 3,
	            'role' => 'service_provider',
	            'status' => 'Active',
	        ]);

	        // many to many attach

	        // $user->specialities()->attach($request->specialities);

	        // $user->services()->attach($request->services);

	        $staff = Staff::create([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'specialty_id' => $request->specialty_id,
                'experience_id' => $request->experience_id,
                'working_time_range_id' => $request->working_time_range_id,
                'slot_duration_minutes' => $request->slot_duration,
                'created_by' => NULL,
            ]);

            $staff->workingDays()->sync($request->working_day_ids);

            foreach($request->services as $service){
                StaffService::create([
                    'user_id' => $user->id,
                    'staff_id' => $staff->id,
                    'service_id' => $service['service_id'],
                    //'duration_id' => $service['duration_id'],
                    'price' => $service['price'],
                ]);
            }

	        DB::commit();

	        return response()->json([
	            'status' => true,
	            'message' => 'Successfully Signup',
	            'data' => $user
	        ]);

	    }catch (Exception $e) {

	        DB::rollback();

	        return response()->json([
	            'status' => false,
	            'message' => $e->getMessage()
	        ]);
	    }
    }

    public function workingDays(Request $request)
    {
    	try
    	{
    		$workingDays = WorkingDay::get();
    		return response()->json(['status'=>count($workingDays)>0, 'data'=>$workingDays]);
    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function workingTimeRanges(Request $request)
    {
    	try
    	{
    		$data = WorkingTimeRange::get();
    		return response()->json(['status'=>count($data)>0, 'data'=>$data]);
    	}catch(Exception $e){
    		return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
    	}
    }

    public function barberSignin(Request $request)
    {   


    	$validator = Validator::make($request->all(), [

	        'login' => 'required|string',
	        'password' => 'required|string',

	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'errors' => $validator->errors()
	        ], 422);
	    }

	    DB::beginTransaction();

    	try {
            // Rate limiting to prevent brute-force attacks
            $key = 'login_attempts:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                //return $this->sendError('Too many login attempts. Please try again later.', 429);
                return response()->json([
	            	'status' => false,
	            	'message' => 'Too many login attempts. Please try again later.',
	            	'token' => "",
	            	'user' => new \stdClass(), 
	            ],429);
            }

            
            $login = $request->login;
            // Find user by phone or email
            $user = User::where('email', $request->login)
                ->orWhere('phone', $request->login)
                ->where('status', "Active")
                ->where('role','service_provider')
                ->first();

            $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            // Validate user and password
            if (Auth::attempt([$fieldType => $login, 'password' => $request->password])) {
                RateLimiter::hit($key, 60); // Increase failed login count (lockout for 1 minute)
                //return $this->sendError('The provided credentials are incorrect.', 401);

                return response()->json([
	            	'status' => false,
	            	'message' => 'The provided credentials are incorrect.',
	            	'token' => "",
	            	'user' => new \stdClass(), 
	            ],401);
            }

            // Reset login attempts after successful login
            RateLimiter::clear($key);

            // Generate API token immediately if OTP is not enabled
            $token = $user->createToken('API Token')->plainTextToken;

            DB::commit();

            return response()->json([
            	'status' => true,
            	'message' => 'Successfully Logged IN',
            	'token' => $token,
            	'user' => $user, 
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Error in SP Login: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine()
            ]);

            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberSignout(Request $request)
    {
    	try
        {   
        	if(user()->role != 'service_provider'){
        		return response()->json(['status'=>false, 'message'=>'Invalid Role'],400);
        	}
            auth()->user()->tokens()->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully Logged Out']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
