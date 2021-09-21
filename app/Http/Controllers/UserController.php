<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
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
        return view('user.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->where('username','!=',Auth::user()->getUsername())->orderByDesc('created_date')->get();
            return Datatables::of($data)               
                ->make(true);
        }
    }

    public function add()
    {
        $type = 'Add';
        return view('user.form',compact('type'));
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = User::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(UserRequest $request, $req)
    {

        try{
            $user = new User;
            if($request->input('id') != ''){
                $user = User::find($request->input('id'));
                $user->updated_by = Auth::user()->getUsername();
            }
            $user->firstName = $request->input('firstName');
            $user->lastName = $request->input('lastName');
            $user->phoneNumber = $request->input('phoneNumber');
            $user->role = $request->input('role');
            $user->email = $request->input('email');
            if(strlen($request->get('password')) > 0){
                $user->password = Hash::make($request->get('password'));
            }
            if($request->input('id') == ''){    
                $fn = str_replace(' ', '', $request->input('firstName'));
                $ln = str_replace(' ', '', $request->input('lastName'));
                $check = ($request->input('lastName'))?strtolower($fn). '.' .strtolower($ln):strtolower($fn);            
                $i=0;
                while (!$check) {
                    $i++;
                    $check = $this.checkUsername($check).$i;            
                }
                $user->username = $check;
                $user->created_by = Auth::user()->getUsername();
            }            
            
            if($user->save()){  
                $type = ($request->input('id') == '')?' save':' update';
                $msg = Auth::user()->getUsername(). $type.' user succesfully : '.json_encode($user);              
                Log::info($msg);
                $this->sharedService->logs($msg);
                echo json_encode(array('status' => 200, 'message' => 'Process Succesfully'));
            }
        }
        catch (Exception $e){   
            Log::error($e->getMessage());  
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully'.($request->input('id') == '')?' save':' update'. ' User: '.$e->getMessage());       
            echo json_encode(array('status' => 301, 'message' => 'Proccess Unsuccessfully'));
        }
            
    }

    public function checkUsername($username)
    {
        $user = User::where('username', $username)->first();
        if($user === null) return true;
            return false;
    }

    public function delete(Request $request)
    {
        try {      
            $id = $request->post('data');
            $user = User::whereIn('rowID', $id);            
            $msg = Auth::user()->getUsername(). ' delete user succesfully : '.json_encode($user->get()->toArray());              
            Log::info($msg);
            $this->sharedService->logs($msg);
            $user->delete();
            echo json_encode(array('status' => 200, 'message' => 'Prosess berhasil dilakukan'));
        }catch (Exception $e){
            Log::error($e->getMessage());
            $this->sharedService->logs(Auth::user()->getUsername().' unsuccessfully delete user: '.$e->getMessage());       
            echo json_encode(array('status' => 301, 'message' => $e->getMessage()));
        }
        
    }
}
