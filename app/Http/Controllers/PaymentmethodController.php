<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paymentmethod;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;

class PaymentmethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth_check');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $methods = Paymentmethod::latest();

            return DataTables::of($methods)
                ->addIndexColumn()

                ->addColumn('name', function($row){
                    return $row->name ?? '';
                })

                ->addColumn('status', function($row){
                    return '<label class="switch">
                        <input class="status-toggle ' . ($row->status === 'Active' ? 'active-data' : 'decline-data') . '" 
                            type="checkbox" 
                            ' . ($row->status === 'Active' ? 'checked' : '') . ' 
                            data-id="'.$row->id.'">
                        <span class="slider round"></span>
                    </label>';
                })

                ->addColumn('action', function($row){

                    $btn = '';

                    $btn .= '<a href="'.route('paymentmethods.show',$row->id).'" 
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i>
                             </a>';

                    $btn .= '&nbsp;';

                    $btn .= '<a href="#" 
                                class="btn btn-danger btn-sm delete-data" 
                                data-id="'.$row->id.'">
                                <i class="fa fa-trash"></i>
                             </a>';

                    return $btn;
                })

                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.paymentmethods.index');
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.paymentmethods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentMethodRequest $request)
    {
        try
        {
            PaymentMethod::create($request->validated());

            $notification=array(
                'message' => 'Successfully a payment method has been added',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $method = PaymentMethod::findOrFail($id);
        return view('admin.paymentmethods.edit', compact('method'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $method = PaymentMethod::findOrFail($id);
            $method->update($request->validated());

            $notification=array(
                'message' => 'Successfully the payment method has been added',
                'alert-type' => 'success',
            );

            return redirect()->back()->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $method = PaymentMethod::findOrFail($id);
            $method->delete();
            return response()->json(['status'=>true, 'message'=>'Successfully deleted']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function paymentMethodStatusUpdate(Request $request)
    {
        try
        {
            $method = Paymentmethod::findorfail($request->id);
            $method->status = $request->status;
            $method->update();
            return response()->json(['status'=>true, 'message'=>'Successfully updated']);
        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }
}
