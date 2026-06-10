<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use App\Models\User;
use App\Http\Requests\NewUserRequest;
class InstallController extends Controller
{
    public function install()
    {
    	return view('install.create_project');
    }

    public function storeInfo(Request $request)
    {
    	try
    	{
    	


            $db_name = env('DB_DATABASE');
            
            $app_url = env('APP_URL');

            $db_username = env('DB_USERNAME');

            $db_password = env('DB_PASSWORD');

            $timezone = env('SELECT_TIMEZONE');

            $path = base_path('.env');
            $test = file_get_contents($path);

            if (file_exists($path)) {
                $replacements = [

                    "DB_DATABASE=$db_name" => "DB_DATABASE=$request->db_name",
                    "APP_URL=$app_url" => "APP_URL=$request->domain_url",
                    "DB_USERNAME=$db_username" => "DB_USERNAME=$request->db_user",
                    "DB_PASSWORD=$db_password" => "DB_PASSWORD=$request->db_password",
                    "SELECT_TIMEZONE=$timezone"  => "SELECT_TIMEZONE=$request->timezone",
                ];

                foreach ($replacements as $search => $replace) {
                    $test = str_replace($search, $replace, $test);
                }

                file_put_contents($path, $test);
            }
            
            return redirect('/admin-register');
    	}catch(Exception $e){
                  
                $message = $e->getMessage();
      
                $code = $e->getCode();       
      
                $string = $e->__toString();       
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }

    public function register()
    {
    	try
    	{   
    		Artisan::call("migrate");
    		return view('admin_register');
    	}catch(Exception $e){
                  
                $message = $e->getMessage();
      
                $code = $e->getCode();       
      
                $string = $e->__toString();       
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }

    public function registerAdmin(NewUserRequest $request)
    {
    	try
    	{     

    	  $user = new User();
    	  $user->name = $request->name;
          $user->user_type_id = 1;
    	  $user->role = 'admin';
    	  $user->email = $request->email;
    	  $user->password = bcrypt($request->password);
    	  $user->save();
    	  return redirect('/');
    		
    	}catch(Exception $e){
                  
                $message = $e->getMessage();
      
                $code = $e->getCode();       
      
                $string = $e->__toString();       
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
                exit;
        }
    }
}
