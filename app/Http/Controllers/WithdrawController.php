<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Withdraw;
use DB;
use App\Models\Staff;
use App\Models\User;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function withdrawLists(Request $request)
    {
    	if ($request->ajax()) {

	        $withdraws = Withdraw::latest();

	        return DataTables::of($withdraws)
	            ->addIndexColumn()

	            ->addColumn('staff_name', function($row){

	                return $row->staff->user->name;
	            })

	            ->addColumn('staff_phone', function($row){

	                return $row->staff->user->phone;
	            })

	            ->addColumn('amount', fn($row) => $row->amount)

	            ->addColumn('status', function($row){
	                $badge = $row->status == 'approved' 
	                    ? '<span class="badge badge-success">Approved</span>' 
	                    : '<span class="badge badge-warning">Pending</span>';

	                return $badge;
	            })

	            ->addColumn('action', function($row){

	                $btn = '';

	                // approve button (only if pending)
	                if($row->status == 'pending'){
	                    $btn .= '<button class="btn btn-success btn-sm approve-btn" data-id="'.$row->id.'">
	                                Approve
	                             </button>';
	                }

	                return $btn;
	            })

	            ->rawColumns(['status','action','staff_name','staff_phone'])
	            ->make(true);
	    }

	    return view('admin.withdraws.index');
    }

    public function withdrawApprove(Request $request)
    {   
    	DB::beginTransaction();
    	try
    	{
    		$withdraw = Withdraw::find($request->id);

    		$staff = Staff::findorfail($withdraw->staff_id);

		    if(!$withdraw){
		        return response()->json([
		            'status' => false,
		            'message' => 'Withdraw not found'
		        ]);
		    }

		    // if($staff->balance < $withdraw->amount)
		    // {
		    // 	return response()->json([
		    //         'status' => false,
		    //         'message' => 'Invalid Withdraw amount'
		    //     ]);
		    // } 	

		    if($withdraw->status == 'approved'){
		        return response()->json([
		            'status' => false,
		            'message' => 'Already approved'
		        ]);
		    }

		    if(empty($request->trx_id)){
		        return response()->json([
		            'status' => false,
		            'message' => 'TRX ID is required'
		        ]);
		    }

		    $withdraw->trx_id = $request->trx_id;
		    $withdraw->status = 'approved';
		    $withdraw->save();

		   
		    $staff->balance+=$withdraw->amount;
		    $staff->update();

		    DB::commit();

		    return response()->json([
		        'status' => true,
		        'message' => 'Withdraw approved successfully'
		    ]);
    	}catch(Exception $e){
    		DB::rollback();
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

}
