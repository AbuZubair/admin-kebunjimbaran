<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use App\Param;

class DeliveryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sharedService;
    private $model;
    private $param = 'delivery_time';

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
        return view('delivery.index');
    }

    public function getData()
    {
        $data = Param::where('param', $this->param)->orderBy('ref_value')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = Param::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(Request $request)
    {

        try{
            $data = new Param;
            if($request->input('id') != ''){
                $data = Param::find($request->input('id'));
            }
         
            $data->ref_value = $request->input('ref_value');  
            $data->ref_label = $request->input('ref_value');   
            $data->param = $this->param;   
            $data->is_active = $request->input('is_active');                  

      
            if($data->save()){ 
                $type = ($request->input('id') == '')?' save':' update'; 
                $msg = Auth::user()->getUsername(). $type.' delivery succesfully : '.json_encode($data);              
                Log::info($msg);
                $this->sharedService->logs($msg);               
                
                echo json_encode(array('status' => 200, 'message' => 'Process Succesfully'));
            }
        }
        catch (Exception $e){   
            Log::error($e->getMessage());    
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully'.($request->input('id') == '')?' save':' update'. ' delivery promo: '.$e->getMessage());            
            echo json_encode(array('status' => 400, 'message' => 'Proccess Unsuccessfully'));
        }
            
    }

    public function delete(Request $request)
    {
        try {      
            $id = $request->post('data');
            $data = Param::whereIn('id', $id);            
            $msg = Auth::user()->getUsername(). ' delete delivery promo succesfully : '.json_encode($data->get()->toArray());              
            Log::info($msg);
            $this->sharedService->logs($msg);
            $data->delete();
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully delete delivery promo: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
        
    }

}
