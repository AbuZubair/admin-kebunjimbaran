<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Library\Services\Shared;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sharedService;

    public function __construct(Shared $sharedService)
    {
        $this->sharedService = $sharedService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        return view('report.index');
    }

    public function getData(Request $request)
    {       
        if($request->input('type') == 'penjualan'){
            $query = DB::table('payment')
                ->leftJoin('order','order.order_no','=','payment.order_no') 
                ->leftJoin('order_detail','order.order_no','=','order_detail.order_no')
                ->leftJoin('product','product.id','=','order_detail.product_id')                            
                ->where('payment_status',1);
                if($request->input('searchYear')!='')$query->where(DB::raw("YEAR(order.transaction_date)"),$request->input('searchYear'));
                if($request->input('searchMonth')!='')$query->where(DB::raw("MONTH(order.transaction_date)"),$request->input('searchMonth'));
            $data = $query
            ->orderBy('order.transaction_date', 'desc')
            ->get()->toArray();                   
            $data = array_map(function ($data) {
                $q = $this->sharedService->getLink($data);
                $data->link = env("WEB_URL")."/order-detail?q=".$q."&order_no=".$data->order_no." ";
                return $data;
            }, $data);
            return Datatables::of($data)               
                ->make(true);
        }else if($request->input('type') == 'penjualanByProduct'){
            $query = DB::table('order_detail')
                ->leftJoin('product','order_detail.product_id','=','product.id')  
                ->leftJoin('order','order_detail.order_no','=','order.order_no')
                ->leftJoin('payment','order_detail.order_no','=','payment.order_no')  
                ->select('order_detail.*','product.name_id','order.transaction_date')                        
                ->where('payment_status',1);
                if($request->input('product')!='')$query->where('order_detail.product_id',$request->input('product'));
                if($request->input('searchYear')!='')$query->where(DB::raw("YEAR(order.transaction_date)"),$request->input('searchYear'));
                if($request->input('searchMonth')!='')$query->where(DB::raw("MONTH(order.transaction_date)"),$request->input('searchMonth'));
            $data = $query
            ->orderBy('order.transaction_date', 'desc')
            ->get()->toArray();                   
            return Datatables::of($data)               
                ->make(true);
        }else{
            $query = DB::table('product')                        
                ->where('is_active','Y');
            $data = $query
            ->orderBy('id', 'desc')
            ->get()->toArray();                              
            return Datatables::of($data)               
                ->make(true);
        }
        
    }

    public function getDropdown()
    {
        
        $data = $this->sharedService->getDropdown();
        echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan', 'data' => $data));
    }

}
