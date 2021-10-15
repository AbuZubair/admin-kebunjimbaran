<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Order;
use App\Payment;
use App\Product;
use Exception;

class OrderController extends Controller
{
 
    private $sharedService;

    public function __construct(Shared $sharedService)
    {
        $this->sharedService = $sharedService;
    }

    public function order(Request $request)
    {
        try {
            $check = $this->checkStock($request);

            if(!$check){
                $resp = array(
                    'status' => false,
                    'message' => "stock unvailable",
                    'data' => []
                );
                return response()->json($resp, 200);
            }else{
                $t = strtotime($request->input('deliveryDate'));
                $order = new Order;
                $order->order_no = time();
                $order->transaction_date = date("Y-m-d");
                $order->total_amount = $request->input('total');
                $order->subtotal_amount = $request->input('subtotal');
                $order->fullname = $request->input('fullname');
                // $order->email = $request->input('email');
                $order->address = $request->input('address');
                $order->phone = $request->input('phone');
                $order->delivery_date = date('y-m-d',$t);
                if($request->input('promoId') !== null)$order->promo_id = $request->input('promoId');
    
                if($order->save()){   
                    $this->orderDetail($request,$order->order_no);
                    $this->createPayment($request,$order->order_no);
                    $lastId = $order->id;         
                    $msg = 'Order created succesfully : '.json_encode($order);              
                    Log::info($msg);
                    $this->sharedService->logs($msg);  
                    $q = $this->sharedService->getLink($order);
                    $order->delivery = date('d-m-Y',$t);
                    $link = env("WEB_URL")."/order-detail?q=".$q."&order_no=".$order->order_no." ";
                    $order->link = (string)$link;
                    $order->amount = number_format($order->total_amount);
                    // $this->sharedService->sendEmail($order->email,$order);
                    $resp = array(
                        'status' => true,
                        'message' => 'Succesfully',
                        'data' => array(
                            'order_no' => $order->order_no,
                            'q' => $q
                        )
                    );
                    return response()->json($resp, 200);
    
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());   
            $this->sharedService->logs('Order create unsuccessfully: '.$e->getMessage());                 
            $resp = array(
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            );
            return response()->json($resp, 500);
        }        
    }

    public function checkStock($request)
    {
        try {
            $status = true;
            $detail = $request->input('detail');
            $products = array_map(function($element) {
                return $element['id'];
            },$detail);
            $product = Product::whereIn('id',$products)->get()->toArray();

            for ($i=0; $i <count($detail) ; $i++){
                foreach ($product as $key => $value) {
                    if($value['id'] == $detail[$i]['id']){
                        if($detail[$i]['qty'] > $value['stock']){
                            $status = false;
                        }
                    }
                }
            }
            return $status;
            
        } catch (Exception $e) {
            Log::error($e->getMessage());   
            $this->sharedService->logs('Order Check create unsuccessfully: '.$e->getMessage());
            return false;
        }
    }

    public function orderDetail($request,$order_no)
    {
        try {
            $detail = $request->input('detail');
           
            for ($i=0; $i <count($detail) ; $i++){
                $prd = Product::find($detail[$i]['id']);
                $prd->stock = $prd->stock -  $detail[$i]['qty'];
                $prd->save();
                $amt = ($detail[$i]['harga_discount'] != 0)?$detail[$i]['harga_discount']:$detail[$i]['harga'];
                $array[] = array(
                    'order_no' => $order_no,
                    'product_id' => $detail[$i]['id'],
                    'quantity' => $detail[$i]['qty'],
                    'harga' => $detail[$i]['harga'],
                    'harga_discount' => $detail[$i]['harga_discount'],
                    'amount' => $amt * $detail[$i]['qty']
                );
            }
            
            DB::table('order_detail')->insert($array);
            Log::info(' Order Detail created : '.json_encode($array)); 
            $this->sharedService->logs('Order Detail create successfully: '.json_encode($array)); 
        } catch (Exception $e) {
            Log::error($e->getMessage());   
            $this->sharedService->logs('Order Detail create unsuccessfully: '.$e->getMessage());
        }
    }

    public function createPayment($request,$order_no)
    {
        try {
            $payment = new Payment;
            $payment->order_no = $order_no;
            $payment->invoice_no = "INV".date('YmdHis');
            $payment->charge_amount = $request->input('total');
            $payment->payment_type = $request->input('paymentMethod');
            if($request->input('paymentMethod')=='transfer')$payment->bank = 'BCA';
           
            if($payment->save()){          
                $msg = 'Payment created succesfully : '.json_encode($payment);              
                Log::info($msg);
                $this->sharedService->logs($msg);  
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());   
            $this->sharedService->logs('Payment create unsuccessfully: '.$e->getMessage());                 
        }
    }

    public function getOrderDetail(Request $request)
    {
        $resp = array(
            'status' => false,
            'message' => "Data not found",
            'data' => []
        );
        try {
            $order = Order::where('order_no', $request->order_no)->get()->first();
            if(isset($order)){
                $check = base64_encode($order->order_no.$order->phone.$order->email.$order->transaction_date);
                if($request->q != $check){
                    $resp['message'] = "Unauthorized";
                    return response()->json($resp, 401);
                }else{
                    $order_detail = DB::table('order_detail')->leftJoin('product','product.id','=','order_detail.product_id')->where('order_no', $request->order_no)->get()->toArray();
                    $payment = Payment::where('order_no', $request->order_no)->get()->first();

                    $resp['message'] = "Succesfully";
                    $resp['status'] = true;
                    $resp['data'] = array(
                        'order' => $order,
                        'order_detail' => $order_detail,
                        'payment' => $payment
                    );
                }
            }

            return response()->json($resp, 200);
        } catch (Exception $e) {
            //throw $th;
        }
        
    }

    public function cancelOrder(Request $request)
    {
        $resp = array(
            'status' => false,
            'message' => "Data not found",
            'data' => []
        );

        try {
            /* Update Order */
            $order = Order::where('order_no',$request->input('order_no'))->get()->first();
            $order->status = 2;
            $order->save();
            /****************/

            /*Update Stock*/
            $detail = DB::table('order_detail')->where('order_no',$request->order_no)->get()->toArray();
            for ($i=0; $i <count($detail) ; $i++){
                $prd = Product::find($detail[$i]->product_id);
                $prd->stock = $prd->stock +  $detail[$i]->quantity;
                $prd->save();
            }
            /***************/

            $resp['message'] = "Succesfully";
            $resp['status'] = true;
            return response()->json($resp, 200);

        } catch (Exception $e) {
            //throw $th;
            $resp['message'] = $e->getMessage();
            return response()->json($resp, 500);
        }
       
        
    }

    public function buktiTrf(Request $request)
    {
        $request->validate([
            'order_no' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg|'
        ]);

        $resp = array(
            'status' => false,
            'message' => "Data not found",
            'data' => []
        );

        try {
            $payment = Payment::where('order_no',$request->input('order_no'))->get()->first();
            $file = $request->file('file');
            $path = public_path('images/bukti_trf');
            $imageName = "transferslip_".time().'.'.$file->getClientOriginalExtension(); 
            $fileName = '/images/bukti_trf/'. $imageName;
            $file->move($path, $imageName);

            $payment->bukti_trf = $fileName;
            if($payment->save()){
                $msg = 'Bukti Transfer created succesfully : '.json_encode($payment);              
                Log::info($msg);
                $this->sharedService->logs($msg);  
                $resp['message'] = "Succesfully";
                $resp['status'] = true;
                $resp['data'] = $imageName;
                return response()->json($resp, 200);
            }
            return response()->json($resp, 301);
        } catch (\Throwable $th) {
            return response()->json($resp, 500);
        }
    }

}
