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
use App\Models\Booking;
use App\Models\Barberfav;
use App\Models\Barberrating;

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

	        'home_service' => 'nullable|in:yes,no',

	        //'special_services' => 'required',

	        'duration' => 'nullable|numeric',

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

            $specialServices = $request->special_services;
            $specialServices = str_replace("'", '"', $specialServices);
            $specialServices = json_decode($specialServices, true);

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
                    'is_special' => $request->has('special_services')&&in_array($service['service_id'],$specialServices)?1:0,
                    'duration' => isset($service['duration'])?$service['duration']:NULL,
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

            $login = $request->input('login');
            $password = $request->input('password');

            $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            $user = User::where('email',$login)->orWhere('phone',$login)->first();
            
            if($user && $user->status == 'Inactive'){
                return response()->json(['status'=>false, 'message'=>'Sorry you are not active user', 'token'=>"", 'user'=>new \stdClass()],403);
            }

            if (Auth::attempt([$fieldType => $login, 'password' => $password])) {
                $token = $user->createToken('MyApp')->plainTextToken;
                return response()->json(['status'=>true,'message'=>'Successfully Logged IN', 'token'=>$token, 'user'=>$user]);
            }

            return response()->json(['status'=>false,'message'=>"Invalid Email/Phone or Password", 'token'=>"", 'user'=>new \stdClass()],401);

        }catch(Exception $e){
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
        	$user = user()->load(['staff.services','staff.workingTimeRange','staff.workingDays']);
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
	        // if ($request->hasFile('image')) {
	        //     $image = $request->file('image');
	        //     $imageName = time() . '_' . $image->getClientOriginalName();
	        //     $image->move(public_path('uploads/users'), $imageName);
	        //     $user->image = $imageName;
	        // }

	        if ($request->file('image')) {
                $file = $request->file('image');
                $name = time() . $user->id . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/users/', $name);
                if($user->image != 'defaults/profile.png')
                {   
                	$existingPath = file_exists(public_path($user->image));
                	if($existingPath){
                		unlink(public_path($user->image));
                	}
                } 	
                $path = 'uploads/users/' . $name;
            }else{
                $path = $user->image;
            }

	        // Update user details
	        if ($request->filled('name')) $user->name = $request->name;
	        if ($request->filled('email')) $user->email = $request->email;
	        if ($request->filled('phone')) $user->phone = $request->phone;
	        //if ($request->filled('password')) $user->password = Hash::make($request->password);
	        $user->image = $path;
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

		            $specialServices = $request->special_services;
		            $specialServices = str_replace("'", '"', $specialServices);
		            $specialServices = json_decode($specialServices, true);

	                // Delete old services
	                StaffService::where('staff_id', $staff->id)->delete();

	                foreach ($services as $service) {
	                    StaffService::create([
	                        'user_id' => $user->id,
	                        'staff_id' => $staff->id,
	                        'service_id' => $service['service_id'],
	                        'price' => $service['price'],
	                        'is_special' => $request->has('special_services')&&in_array($service['service_id'],$specialServices)?1:0,
                            'duration' => isset($service['duration'])?$service['duration']:NULL, 
	                    ]);
	                }
	            }
	        }

	        DB::commit();

	        return response()->json([
	            'status' => true,
	            'message' => 'Profile updated successfully',
	            'user' => $user->fresh()->load('staff.workingTimeRange','staff.workingDays', 'staff.services') // reload relations
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

			    // 'trustBarbers' => User::orderBy('id','DESC')
			    // ->limit(4)
			    // ->get(),

			    'trustBarbers' => User::whereHas('staff')->with('staff')
			    ->limit(4)
			    ->get(),


			    // 'homeSerivceBarbers' => User::where('home_service','yes')
			    // ->limit(10)
			    // ->get(),

			    'homeSerivceBarbers' => User::whereHas('staff')
			    ->where('home_service','yes')
			    ->with('staff')
			    ->limit(10)
			    ->get(),

			    // 'quick_book' => User::whereHas('staff')
			    // ->whereHas('barberfavs')
			    // ->with('staff')
			    // ->where('user_id',user()->id)
			    // ->limit(10)
			    // ->get(),

			    'quick_book' => User::whereHas('staff.barberfavs', function ($q) {
			        $q->where('user_id', user()->id);
			    })
			    ->with('staff')
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

    public function changePassword(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
                'current_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            //$message = $user->changePassword($request,$user);

            if (!Hash::check($request->current_password, $user->password)) {
            
               return response()->json(['status'=>false, 'message'=>"The current password is incorrect"],400);
            } 

            $user->password = Hash::make($request->new_password);
            $user->update();

            return response()->json(['status'=>true, 'message'=>"Your password has been changed"],200);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function saveBooking(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'staff_id' => 'required|integer|exists:staffs,id',
                'staff_service_id' => 'required|integer|exists:staff_services,id',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'booking_time' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();

            $staff = Staff::findorfail($request->staff_id);

            if($staff->status != 'Available')
            {
            	return response()->json(['status'=>false, 'message'=>"The barber is not Available Now!", 'data'=>new \stdClass()],429); 
            }	

            $booking = new Booking();
            $booking->user_id = $user->id;
            $booking->staff_service_id = $request->staff_service_id;
            $booking->staff_id = $request->staff_id;
            $booking->booking_date = $request->booking_date;
            $booking->booking_time = $request->booking_time;
            $booking->booking_timestamp = getTimeStamP($request);
            $booking->timestamp = time();
            $booking->status = 'pending';
            $booking->save();

            return response()->json(['status'=>true, 'message'=>"Successfully Booking request sent to the barber", 'data'=>$booking]);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberBookingAccept(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            $user->load('staff');

            $booking = Booking::findorfail($request->booking_id);

            if($booking->staff_id == $user->staff->id)
            {
            	$booking->status = 'barber_accept';
            	$booking->update();
            	return response()->json(['status'=>true, 'booking_id'=>intval($booking->id), 'message'=>'Successfully accept']);
            }

            return response()->json(['status'=>false, 'booking_id'=>0, 'message'=>'Invalid Staff'],429);  	

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberBookingReject(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            $user->load('staff');

            $booking = Booking::findorfail($request->booking_id);

            if($booking->staff_id == $user->staff->id)
            {
            	$booking->status = 'barber_reject';
            	$booking->update();
            	return response()->json(['status'=>true, 'booking_id'=>intval($booking->id), 'message'=>'Successfully reject']);
            }

            return response()->json(['status'=>false, 'booking_id'=>0, 'message'=>'Invalid Staff'],429);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberDetails($id)
    {
    	try
    	{   
    	    $user = User::findorfail($id);
        	$user = $user->load(['staff.workingTimeRange','staff.workingDays','staff.services']);
        	return response()->json(['status'=>true, 'user'=>$user]);
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userRejectBooking(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            //$user->load('staff');

            $booking = Booking::findorfail($request->booking_id);

            if($booking->user_id == $user->id)
            {
            	$booking->status = 'user_reject';
            	$booking->update();
            	return response()->json(['status'=>true, 'booking_id'=>intval($booking->id), 'message'=>'Successfully reject']);
            }

            return response()->json(['status'=>false, 'booking_id'=>0, 'message'=>'Invalid User'],429);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function bookingLists(Request $request)
    {
    	try
    	{   

    		$user = user();
            $user->load('staff');

    		$query = Booking::query();
    		if($request->has('from_date'))
    		{
    			$query->where('booking_date','>=',$request->from_date);
    		}
    		if($request->has('to_date'))
    		{
    			$query->where('booking_date', '<=', $request->to_date);
    		}
    		if($request->has('status'))
    		{
    			$query->where('status',$request->status);
    		}
    		if ($request->is_paginate == 1) {

	            $per_page = $request->per_page ?? 10;

	            $data = $query->with('user')->where('staff_id',$user->staff->id)->latest()->paginate($per_page);

	        } else {

	            $data = $query->with('user')->where('staff_id',$user->staff->id)->latest()->get();
	        }

	        return response()->json($data);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberBookingCancel(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            $user->load('staff');

            $booking = Booking::findorfail($request->booking_id);

            if($booking->staff_id == $user->staff->id)
            {
            	$booking->status = 'barber_cancel';
            	$booking->update();
            	return response()->json(['status'=>true, 'booking_id'=>intval($booking->id), 'message'=>'Successfully cancel']);
            }

            return response()->json(['status'=>false, 'booking_id'=>0, 'message'=>'Invalid Staff'],429);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userDetails()
    {
    	try
    	{
    		$user = user();
    		return response()->json(['status'=>true, 'user'=>$user]);
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userProfileUpdate(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'name' => 'required|string',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'image' => 'nullable'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();

            //return $user;

            $countEmail = User::where('email',$request->email)->where('id','!=',$user->id)->count();

            $countPhone = User::where('phone',$request->phone)->where('id','!=',$user->id)->count();

            if($countEmail > 0){
            	return response()->json(['status'=>false, 'message'=>'Already the email has been taken', 'user'=> new \stdClass()],422);
            }

            if($countPhone > 0){
            	return response()->json(['status'=>false, 'message'=>'Already the phone has been taken', 'user'=> new \stdClass()],422);
            }

            if ($request->file('image')) {
                $file = $request->file('image');
                $name = time() . $user->id . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/users/', $name);
                $path = 'uploads/users/' . $name;
            }else{
                $path = $user->image;
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->image = $path;
            $user->update();

            return response()->json(['status'=>true, 'message'=>'Successfully updated', 'user'=>$user]);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function barberFav(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'staff_id' => 'required|integer|exists:staffs,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $count = Barberfav::where('user_id',user()->id)->where('staff_id',$request->staff_id)->count();
            if($count > 0){
            	return response()->json(['status'=>false, 'message'=>'Already listed as Fav'],429); 
            }

            $fav = new Barberfav();
            $fav->user_id = user()->id;
            $fav->staff_id = $request->staff_id;
            $fav->save();

            return response()->json(['status'=>true, 'message'=>'Successfully added']);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function myFavLists()
    {
    	try
    	{
    		$data = Barberfav::with(['staff.user','staff.services'])->where('user_id',user()->id)->get();
    		return response()->json(['status'=>count($data) > 0, 'data'=>$data]);
    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function saveBarberRating(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'staff_id' => 'required|integer|exists:staffs,id',
                'rate' => 'required|integer|max:5',
                'remarks' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $count = Barberrating::where('user_id',user()->id)->where('staff_id',$request->staff_id)->count();
            if($count > 0){
            	return response()->json(['status'=>false, 'message'=>'Already rating provided for the barber'],429); 
            }

            $rate = new Barberrating();
            $rate->user_id = user()->id;
            $rate->staff_id = $request->staff_id;
            $rate->rate = $request->rate;
            $rate->remarks = $request->remarks;
            $rate->save();

            return response()->json(['status'=>true, 'message'=>'Successfully added']);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userCancelBooking(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $user = user();
            //$user->load('staff');

            $booking = Booking::findorfail($request->booking_id);

            if($booking->user_id == $user->id)
            {
            	$booking->status = 'user_cancel';
            	$booking->update();
            	return response()->json(['status'=>true, 'booking_id'=>intval($booking->id), 'message'=>'Successfully cancel']);
            }

            return response()->json(['status'=>false, 'booking_id'=>0, 'message'=>'Invalid User'],429);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function userRescheduleBooking(Request $request)
    {
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'booking_time' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $booking = Booking::findorfail($request->booking_id);
            $booking->booking_date = $request->booking_date;
            $booking->booking_time = $request->booking_time;
            $booking->timestamp = time();
            $booking->reschedule = 'Yes';
            $booking->status = 're-scheduled';
            $booking->save();

            return response()->json(['status'=>true, 'message'=>"Successfully Reschedule the booking", 'data'=>$booking]);

    	}catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function bookingStatusChange(Request $request)
    {  
    	DB::beginTransaction();
    	try
    	{
    		$validator = Validator::make($request->all(), [
                //'user_id' => 'required|integer|exists:users,id',
                'booking_id' => 'required|integer|exists:bookings,id',
                'status' => 'required|in:service_start,paid,completed',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, 
                    'message' => 'Please fill all requirement fields', 
                    'data' => $validator->errors()
                ], 422);  
            }

            $booking = Booking::findorfail($request->booking_id);

            if($booking->status == 'paid' && $request->status == 'paid')
            {
            	return response()->json(['status'=>false, 'message'=>"Already the order's status is {$request->status}"],429);
            }

            if($booking->status == 'completed' && $request->status == 'completed')
            {
            	return response()->json(['status'=>false, 'message'=>"Already the order's status is {$request->status}"],429);
            }

            if($booking->status == 'paid' && $request->status == 'service_start')
            {
            	return response()->json(['status'=>false, 'message'=>"Already the order's status is {$request->status}"],429);
            }

            if($booking->status == 'completed' && $request->status == 'service_start')
            {
            	return response()->json(['status'=>false, 'message'=>"Already the order's status is {$request->status}"],429);
            }
            

            // if($request->status == 'completed' && $request->status == 'completed')
            // {
            // 	return response()->json(['status'=>false, 'message'=>"All the order's status is {$request->status}"],429);
            // }


            $booking->status = $request->status;
            $booking->update();

            $service = StaffService::where('id',$booking->staff_service_id)->first();
            $staff = Staff::where('id',$booking->staff_id)->first();

            if($request->status == 'service_start')
            {
            	$staff->current_status = 'Busy';
            	$staff->update();
            }	

            if($request->status == 'paid')
            {
            	
            	$staff->balance+=$service->price;
            	$staff->update();
            }

            if($request->status == 'completed')
            {
            	$staff->current_status = 'Available';
            	$staff->update();
            }  

            DB::commit();

            return response()->json(['status'=>true, 'message'=>"Successfully {$request->status}"]);

    	}catch(Exception $e){
    		DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
	
}
