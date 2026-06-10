<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Withdraw;
use App\Models\Service;
use App\Models\Speciality;

class DashboardController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function Dashboard()
    {
    	try
    	{   
            $totalBarbers = User::where('role','service_provider')->count();
            $totalUsers = User::where('role','user')->count();
            $totalServices = Service::count();
            $totalSpecialities = Speciality::count();
            $totalBooking = Booking::sum('amount');
            $todayBooking = Booking::where('booking_date',date('Y-m-d'))->count();
            $totalWithdraw = Withdraw::sum('amount');
            $todayWithdraw = Withdraw::where('date',date('Y-m-d'))->sum('amount');
    		return view('layouts.app',compact('totalBarbers','totalUsers','totalServices','totalSpecialities','totalBooking','todayBooking','totalWithdraw','todayWithdraw')); 
    	}catch(Exception $e){
                  
                $message = $e->getMessage();
      
                $code = $e->getCode();       
      
                $string = $e->__toString();       
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }
}
