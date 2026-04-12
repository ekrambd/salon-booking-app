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

	        //'services' => 'required|array',

	        //'services.*' => 'exists:services,id',

	        // 'working_day_ids' => 'required|array',
	        // 'working_day_ids.*' => 'exists:working_days,id',

	        'slot_duration' => 'required|numeric',

	        'working_time_range_id' => 'required|integer|exists:working_time_ranges,id',

	        'home_service' => 'nullable|in:yes,no'

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

	        if ($request->file('image')) {
                $file = $request->file('image');
                $name = time() . $count . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/users/', $name);
                $path = 'uploads/users/' . $name;
            }else{
                $path = 'defaults/profile.png';
            }

	        $user = User::create([
	            'name' => $request->name,
	            'email' => $request->email,
	            'phone' => $request->phone,
	            'password' => bcrypt($request->password),
	            'image' => $path,
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
                'home_service' => $request->home_service,
                'created_by' => NULL,
            ]);

	        $services = $request->services;

	        $services = str_replace("'", '"', $services);

            $services = json_decode($services, true);

            $workingDayIds = json_decode($request->working_day_ids,true);

            //return $services;

            $staff->workingDays()->sync($workingDayIds);
            

            foreach($services as $service){
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
        	if(!checkRole('service_provider')){
        		return response()->json(['status'=>false, 'message'=>'Invalid Role'],400);
        	}
            auth()->user()->tokens()->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully Logged Out']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberProfile(Request $request)
    {
    	try
    	{
        	$user = user()->load(['staff.workingTimeRange','staff.workingDays','staff.services']);
        	return response()->json(['status'=>true, 'user'=>$user]);
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberProfileUpdate(Request $request)
	{
	    $user = auth()->user(); // get the authenticated barber

	    $validator = Validator::make($request->all(), [
	        'name' => 'sometimes|required|string|max:255',
	        'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
	        'phone' => 'sometimes|required|unique:users,phone,' . $user->id,
	        'password' => 'sometimes|nullable|min:6|same:confirm_password',
	        'confirm_password' => 'sometimes|required_with:password',
	        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
	        // 'services' => 'sometimes|array',
	        // 'working_day_ids' => 'sometimes|array',
	        // 'working_day_ids.*' => 'exists:working_days,id',
	        'slot_duration' => 'sometimes|numeric',
	        'working_time_range_id' => 'sometimes|integer|exists:working_time_ranges,id',
	        'branch_id' => 'sometimes|integer|exists:branches,id',
	        'specialty_id' => 'sometimes|integer|exists:specialties,id',
	        'experience_id' => 'sometimes|integer|exists:experiences,id',
	    ]);

	    if ($validator->fails()) {
	        return response()->json([
	            'status' => false,
	            'errors' => $validator->errors()
	        ], 422);
	    }

	    DB::beginTransaction();

	    try {
	        // Update user image
	        if ($request->hasFile('image')) {
	            $image = $request->file('image');
	            $imageName = time() . '_' . $image->getClientOriginalName();
	            $image->move(public_path('uploads/users'), $imageName);
	            $user->image = $imageName;
	        }

	        // Update user details
	        if ($request->filled('name')) $user->name = $request->name;
	        if ($request->filled('email')) $user->email = $request->email;
	        if ($request->filled('phone')) $user->phone = $request->phone;
	        //if ($request->filled('password')) $user->password = Hash::make($request->password);

	        $user->save();

	        // Update Staff details
	        $staff = $user->staff; // assuming User hasOne Staff

	        if ($staff) {
	            //if ($request->filled('branch_id')) $staff->branch_id = $request->branch_id;
	            //if ($request->filled('specialty_id')) $staff->specialty_id = $request->specialty_id;
	            //if ($request->filled('experience_id')) $staff->experience_id = $request->experience_id;
	            if ($request->filled('working_time_range_id')) $staff->working_time_range_id = $request->working_time_range_id;
	            if ($request->filled('slot_duration')) $staff->slot_duration_minutes = $request->slot_duration;

	            $staff->save();

	            // Update working days
	            if ($request->filled('working_day_ids')) {
	            	$workingDayIds = json_decode($request->working_day_ids,true);
	                $staff->workingDays()->sync($workingDayIds);
	            }

	            // Update services
	            if ($request->filled('services')) {

	            	$services = $request->services;

			        $services = str_replace("'", '"', $services);

		            $services = json_decode($services, true);
	                // Delete old services
	                StaffService::where('staff_id', $staff->id)->delete();

	                foreach ($services as $service) {
	                    StaffService::create([
	                        'user_id' => $user->id,
	                        'staff_id' => $staff->id,
	                        'service_id' => $service['service_id'],
	                        'price' => $service['price'],
	                    ]);
	                }
	            }
	        }

	        DB::commit();

	        return response()->json([
	            'status' => true,
	            'message' => 'Profile updated successfully',
	            'data' => $user->fresh()->load('staff.workingDays', 'staff.services') // reload relations
	        ]);

	    } catch (Exception $e) {
	        DB::rollback();
	        return response()->json([
	            'status' => false,
	            'message' => $e->getMessage()
	        ], 500);
	    } 
	}
    

    public function userSignup(Request $request)
    {
    	try
    	{   

    	    $validator = Validator::make($request->all(), [

		        'name' => 'required|string',
		        'email' => 'nullable|email',
		        'phone' => 'required|string',
		        'password' => 'required|string',
		        'confirm_password' => 'required|string|same:password',
		        'image' => 'nullable'
		    ]);

		    if ($validator->fails()) {
		        return response()->json([
		            'status' => false,
		            'errors' => $validator->errors()
		        ], 422);
		    }

		    $countPhone = User::where('phone',$request->phone)->count();

		    $countEmail = User::where('email',$request->email)->count();

		    if($countPhone > 0){
		    	return response()->json(['status'=>false, 'message'=>'The phone has already been taken', 'user'=>new \stdClass()],422);
		    }

		    if($countEmail > 0){
		    	return response()->json(['status'=>false, 'message'=>'The email has already been taken', 'user'=>new \stdClass()],422);
		    }

    		$count = User::count();
	        $count+=1;

	        if ($request->file('image')) {
                $file = $request->file('image');
                $name = time() . $count . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/users/', $name);
                $path = 'uploads/users/' . $name;
            }else{
                $path = 'defaults/profile.png';
            }

            $user = new User();
            $user->name = $request->name;
            $user->user_type_id = 2;
            $user->role = 'user';
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);
            $user->image = $path;
            $user->status = 'Active';
            $user->save();

            return response()->json(['status'=>true, 'message'=>'Successfully Signup', 'user'=>$user]);


    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userSignin(Request $request)
	{
	    try
	    {
	        $validator = Validator::make($request->all(), [
	            'login' => 'required|string',
	            'password' => 'required|string',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false, 
	                'message' => 'Please fill all requirement fields', 
	                'data' => $validator->errors()
	            ], 422);  
	        }

	        $login = $request->login;
	        $password = $request->password;

	        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

	        if (!Auth::attempt([$fieldType => $login, 'password' => $password])) {
	            return response()->json([
	                'status'=>false,
	                'message'=>"Invalid Email/Phone or Password",
	                'token'=>"",
	                'user'=>new \stdClass()
	            ],401);
	        }

	        $user = Auth::user();

	        if($user->status == 'Inactive'){
	            return response()->json([
	                'status'=>false,
	                'message'=>'Sorry you are not active user',
	                'token'=>"",
	                'user'=>new \stdClass()
	            ],403);
	        }

	        $token = $user->createToken('MyApp')->plainTextToken;

	        return response()->json([
	            'status'=>true,
	            'message'=>'Successfully Logged IN',
	            'token'=>$token,
	            'user'=>$user
	        ]);

	    }catch(Exception $e){
	        return response()->json([
	            'status'=>false,
	            'code'=>$e->getCode(),
	            'message'=>$e->getMessage()
	        ],500);
	    }
	}

    public function userSignout(Request $request)
    {
    	try
        {
            auth()->user()->tokens()->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully Logged Out']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function homeBarberLists(Request $request)
    {
    	try
    	{
    		// $liveBarberAvailability = User::whereHas('staff')->with('staff')->limit(10)->get();
    		// $trustBarbers = User::orderBy('id','DESC')->limt(4)->get();
    		// $homeSerivceBarbers = User::where('home_service','yes')->limit(10)->get();
    		// $popularServices = Service::orderBy('hit_count',1)->limit(4)->get();

    		$data = [
			    'liveBarberAvailability' => User::whereHas('staff', function($q){
			        $q->where('current_status','Available');
			    })
			    ->with('staff')
			    ->limit(10)
			    ->get(),

			    'trustBarbers' => User::orderBy('id','DESC')
			    ->limit(4)
			    ->get(),

			    'homeSerivceBarbers' => User::where('home_service','yes')
			    ->limit(10)
			    ->get(),

			    'popularServices' => Service::orderBy('hit_count','DESC')
			    ->limit(4)
			    ->get()
			];

			return response()->json(['status'=>true, 'data'=>$data]);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function changeActivationStatus(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
	            'status' => 'required|in:online,offline',
	        ]);

	        if ($validator->fails()) {
	            return response()->json([
	                'status' => false, 
	                'message' => 'Please fill all requirement fields', 
	                'data' => $validator->errors()
	            ], 422);  
	        }

	        $user = user();
	        $user->activation_status = $request->status;
	        $user->save();

	        return response()->json(['status'=>true, 'message'=>"Successfully {$request->status}"]);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
	
}
