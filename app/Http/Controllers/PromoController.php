<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use App\Promo;
use App\Http\Requests\PromoRequest;

class PromoController extends Controller
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
        return view('promo.index');
    }

    public function getData(Request $request)
    {
        $data = Promo::latest()->orderByDesc('created_date')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = Promo::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(PromoRequest $request)
    {

        try{
            $data = new Promo;
            if($request->input('id') != ''){
                $data = Promo::find($request->input('id'));
                $data->updated_by = Auth::user()->getUsername();
            }
                                   
            $data->promo_name = $request->input('promo_name');  
            $data->kode_promo = $request->input('kode_promo');   
            $data->is_active = $request->input('is_active');                
            if($request->input('discount')!='')$data->discount = $request->input('discount');    
            if($request->input('discount_amount')!='')$data->discount_amount = $request->input('discount_amount');    

            if($request->input('id') == ''){ 
                $data->created_by = Auth::user()->getUsername();
            }            
            
            if($data->save()){ 
                $type = ($request->input('id') == '')?' save':' update'; 
                $msg = Auth::user()->getUsername(). $type.' promo succesfully : '.json_encode($data);              
                Log::info($msg);
                $this->sharedService->logs($msg);               
                
                echo json_encode(array('status' => 200, 'message' => 'Process Succesfully'));
            }
        }
        catch (Exception $e){   
            Log::error($e->getMessage());    
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully'.($request->input('id') == '')?' save':' update'. ' promo: '.$e->getMessage());            
            echo json_encode(array('status' => 400, 'message' => 'Proccess Unsuccessfully'));
        }
            
    }

    public function delete(Request $request)
    {
        try {      
            $id = $request->post('data');
            $data = Promo::whereIn('id', $id);            
            $msg = Auth::user()->getUsername(). ' delete promo succesfully : '.json_encode($data->get()->toArray());              
            Log::info($msg);
            $this->sharedService->logs($msg);
            $data->delete();
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully delete promo: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
        
    }


}
