<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use Illuminate\Support\Facades\Hash;
use App\Order;
use App\Payment;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sharedService;
    private $model;

    public function __construct(Shared $sharedService,Model $model)
    {
        $this->sharedService = $sharedService;
        $this->model = $model;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        return view('order.index');
    }

    public function detail(Request $request)
    {
        $order_no = $request->order_no;
        return view('order.form',compact('order_no'));
    }

    public function getData(Request $request)
    {
        $query = Order::select('order.*','payment.payment_status','payment.charge_amount','payment.payment_type',
        DB::raw("GROUP_CONCAT(CONCAT(product.name_id, '-', order_detail.amount)) AS products"))
        ->leftJoin('payment','order.order_no','=','payment.order_no')
        ->leftJoin('order_detail','order.order_no','=','order_detail.order_no')
        ->leftJoin('product','product.id','=','order_detail.product_id');
        if($request->input('order_no')!='')$query->where('order.order_no',$request->input('order_no'));
        $query->groupBy('order.order_no');
        $data = $query->orderByDesc('created_date')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function getByNo(Request $request)
    {
        $data = Order::select('order.*','payment.charge_amount','payment.payment_type',
            'payment.invoice_no','payment.bank','payment.payment_status','payment.bukti_trf','promo.*')
            ->leftJoin('payment','order.order_no','=','payment.order_no')
            ->leftJoin('promo','order.promo_id','=','promo.id')
            ->where('order.order_no',$request->order_no)->get()->first();

        if(isset($data)){
            $order_detail = DB::table('order_detail')->leftJoin('product','product.id','=','order_detail.product_id')
            ->where('order_no', $request->order_no)->get()->toArray();

            $result = array(
                'data' => $data,
                'detail' => $order_detail
            );
            echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $result));
        }else{
            echo json_encode(array('status' => 204, 'message' => 'Data Not Found', 'data' => []));
        }
        
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = Order::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(Request $request)
    {
        try{
            $order = new Order;
            if($request->input('id') != ''){
                $order = Order::find($request->input('id'));
                $order->updated_by = Auth::user()->getUsername();
            }
            $order->name_id = $request->input('name_id');
            $order->name_en = $request->input('name_en');
            $order->harga = $request->input('harga');
            if($request->input('harga_discount')!='')$order->harga_discount = $request->input('harga_discount');
            $order->satuan = $request->input('satuan');
            $order->ukuran = $request->input('ukuran');
            $order->kategori = $request->input('kategori');
            $order->stock = $request->input('stock');   
            $order->is_active = $request->input('is_active');         
            if($request->input('id') == ''){                                
                $order->created_by = Auth::user()->getUsername();
            }            

            if($request->input('uploaded') == 0){
                $file = $request->file('photo');
                $path = public_path('images');
                $imageName = time().'.'.$file->getClientOriginalExtension(); 
                $fileName = '/images/'. $imageName;
                $file->move($path, $imageName);
                $order->path_photo = $fileName; 
            }
            
            if($order->save()){   
                $type = ($request->input('id') == '')?' save':' update';             
                $msg = Auth::user()->getUsername(). $type.' order succesfully : '.json_encode($order);              
                Log::info($msg);
                $this->sharedService->logs($msg);  
                echo json_encode(array('status' => 200, 'message' => 'Process Succesfully'));
            }
        }
        catch (Exception $e){   
            Log::error($e->getMessage());   
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully'.($request->input('id') == '')?' save':' update'. ' product: '.$e->getMessage());                 
            echo json_encode(array('status' => 400, 'message' => 'Proccess Unsuccessfully'));
        }
            
    }

    public function delete(Request $request)
    {
        try {      
            $id = $request->post('data');
            $data = Order::whereIn('id', $id);            
            $msg = Auth::user()->getUsername(). ' delete product succesfully : '.json_encode($data->get()->toArray());              
            Log::info($msg);
            $this->sharedService->logs($msg);
            $data->delete();
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully delete product: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
        
    }

    public function updatePayment(Request $request)
    {
        try {
            $order_no = isset($_POST['data'])?$request->post('data'):array($request->post('order_no'));
            $data = Payment::whereIn('order_no', $order_no)->get(); 
            foreach ($data as $key => $value) {
                $value->payment_status = 1; 
                if($value->save()){
                    $msg = Auth::user()->getUsername(). ' update payment status succesfully : '.json_encode($data->toArray());              
                    Log::info($msg);
                    $this->sharedService->logs($msg);
                }
            }
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully update payment status: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
    }

    public function updateKirim(Request $request)
    {
        try {
            $order_no = isset($_POST['data'])?$request->post('data'):array($request->post('order_no'));
            $data = Order::whereIn('order_no',  $order_no)->get(); 
            foreach ($data as $key => $value) {
                $payment = Payment::where('order_no', $value->order_no)->get()->first(); 
                $value->status_kirim = $request->post('status'); 
                if($request->post('status') == 2){
                    $value->status = 1;
                }

                $value->save();
                $msg = Auth::user()->getUsername(). ' update status kirim succesfully : '.json_encode($value->toArray());              
                Log::info($msg);
                $this->sharedService->logs($msg);
                
                if($request->post('status') == 2){
                    $payment->payment_status = 1; 
                    $payment->save();
    
                    $msg = Auth::user()->getUsername(). ' update status payment succesfully : '.json_encode($payment->get()->toArray());              
                    Log::info($msg);
                    $this->sharedService->logs($msg);    
                }
            }
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
           
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully update status kirim: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
    }

}
