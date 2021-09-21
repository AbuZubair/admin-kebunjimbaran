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
use App\Http\Requests\WhitelistRequest;

class WhitelistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sharedService;
    private $model;
    private $param = 'whitelist_vcr';

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
        return view('whitelist.index');
    }

    public function getData()
    {
        $data = Param::where('param', $this->param)->orderByDesc('id')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = Param::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(WhitelistRequest $request)
    {

        try{
            $data = new Param;
            if($request->input('id') != ''){
                $data = Param::find($request->input('id'));
            }
                                   
            $msisdn = $this->normalizeMsisdn($request->input('ref_value'));
            if(!$msisdn){
                echo json_encode(array('status' => 400, 'message' => 'Format nomor diawali "62" atau "08"'));
                exit();
            }
            $data->ref_value = $msisdn;  
            $data->ref_label = $msisdn;   
            $data->param = $this->param;   
            $data->is_active = $request->input('is_active');                  

      
            if($data->save()){ 
                $type = ($request->input('id') == '')?' save':' update'; 
                $msg = Auth::user()->getUsername(). $type.' whitelist succesfully : '.json_encode($data);              
                Log::info($msg);
                $this->sharedService->logs($msg);               
                
                echo json_encode(array('status' => 200, 'message' => 'Process Succesfully'));
            }
        }
        catch (Exception $e){   
            Log::error($e->getMessage());    
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully'.($request->input('id') == '')?' save':' update'. ' whitelist promo: '.$e->getMessage());            
            echo json_encode(array('status' => 400, 'message' => 'Proccess Unsuccessfully'));
        }
            
    }

    public function delete(Request $request)
    {
        try {      
            $id = $request->post('data');
            $data = Param::whereIn('id', $id);            
            $msg = Auth::user()->getUsername(). ' delete whitelist promo succesfully : '.json_encode($data->get()->toArray());              
            Log::info($msg);
            $this->sharedService->logs($msg);
            $data->delete();
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully delete whitelist promo: '.$e->getMessage());       
            echo json_encode(array('status' => 400, 'message' => $e->getMessage()));
        }
        
    }

    public function normalizeMsisdn($msisdn)
    {
        $normalize = substr($msisdn,0,2);
    
        switch ($normalize) {
            case '62':
                $msisdn = $msisdn;
                break;
            case '08':
                $msisdn = '62' . substr($msisdn,1);
                break;
            default:
                $msisdn = false;
                break;
        }

        return $msisdn;
    }


}
