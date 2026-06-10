<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appnotification;
use App\Models\User;
use DataTables;
class NotificationController extends Controller
{   

	public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function addNotification()
    {
    	return view('admin.notifications.create'); 
    }

    public function saveNotification(Request $request)
    {
    	try
    	{
    		$appNotification = new Appnotification();
    		$appNotification->title = $request->title;
    		$appNotification->description = $request->description;
    		$appNotification->date = date('Y-m-d');
    		$appNotification->time = date('h:i:s a');
    		$appNotification->save();
    		$users = User::where('role','!=','admin')->get();
    		foreach($users as $user)
    	    {
    	    	sendPush($request->title,$request->description,$user->device_token);
    	    }		
    		$notification=array(
                'message' => 'Successfully a notification has been added',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);
    	}catch(\Exception $e) {
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function allNotification(Request $request)
    {
    	try
        {
            if($request->ajax()){

                $notifications = Appnotification::select('*')->latest();

                return Datatables::of($notifications)
                    ->addIndexColumn()

                    

                    ->addColumn('action', function($row){

                        $btn = "";
                        $btn .= '&nbsp;';

                        


                        $btn .= ' <a href="#" class="btn btn-danger btn-sm delete-notification action-button" data-id="'.$row->id.'"><i class="fa fa-trash"></i></a>';

                        return $btn;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('admin.notifications.index');
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!!!'
            ],500);
        }
    }

    public function deleteNotification($id)
    {
    	try
    	{
    		$data = Appnotification::findorfail($id);
    		$data->delete();
    		return response()->json(['status'=>true, 'message'=>'Successfully the notification has been deleted']);
    	}catch(\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!!!'
            ],500);
        }
    }
}
