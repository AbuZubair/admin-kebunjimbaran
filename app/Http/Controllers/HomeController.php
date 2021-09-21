<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use Yajra\Datatables\Datatables;
use App\Approval;
use App\Client;
use App\Akun;
use App\Project;
use App\Http\Requests\RegOnlineRequest;
use App\Http\Requests\NewPasienRequest;
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $sharedService;
    private $model;

    public function __construct(Shared $sharedService, Model $model)
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
        // $data_stat = new \stdClass();        
        // $data_stat->open = $this->getCount(0);
        // $data_stat->ament = $this->getCount(1);
        // $data_stat->close = $this->getCount(2);
        // $data_stat->trans = $this->getCount(3);
        // $total = DB::select(DB::raw("SELECT SUM(transValue) as total FROM transaction LEFT JOIN approval ON transaction.rowID=approval.transID WHERE transYear=".date('Y')." AND transMonth=".date('m'). " AND transType='D' AND approval.status=1 AND ((transaction.isTransfer = 1 AND transaction.statusTransfer = 1) OR transaction.isTransfer = 0)"));       
        // $data = array(
        //     'stat' => $data_stat,
        //     'waiting' => $this->getApprovalByStatus('0,-1'),
        //     'approved' => $this->getApprovalByStatus('1'),
        //     'total' => number_format($total[0]->total)
        // );        
        $data = [];
        return view('dashboard', compact('data'));
    }
    
    public function getCount($status){
        $query = Approval::where('status',0);
        if(in_array(Auth::user()->getRole(), [2]))$query->where('approval.requestor',Auth::user()->getId()); 
        return $query->where('approvalType', $status)->count();
    }

    public function query()
    {
        $query = DB::table('approval')        
        ->leftJoin('masterproject', 'masterproject.rowID', '=', 'approval.projectID')
        ->leftJoin('transaction', 'transaction.rowID', '=', 'approval.transID')
        ->select('approval.*', 'masterproject.projectName as project', 'transaction.tansDesc as transaksi', 'transaction.isTransfer', 'transaction.statusTransfer');  
        if(in_array(Auth::user()->getRole(), [2]))$query->where('approval.requestor',Auth::user()->getId());    
        if(in_array(Auth::user()->getRole(), [4])){
            $query->where('approval.approvalType',3);     
        }else{
            $query->orderBy('approval.status');
        }
        return $query;
    }

    public function getDataApproval(Request $request)
    {
        $query = $this->query();
        $data = $query->orderByDesc('approval.created_date')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function getApprovalById(Request $request)
    {
        $query = $this->query();
        $data = $query->where('approval.rowID', $request->get('id'))->first();
        $changed = json_decode($data->changedVal);
        
        foreach ($changed as $k=>$obj) {
            switch ($k) {
                case 'transClientID':
                    $client = Client::find($obj);
                    $changed->client = $client->clientName;
                    break;
                case 'transAkunID':
                    $akun = Akun::where('idAkun',$obj)->first();
                    $changed->akun = $akun->namaAkun;
                    break;
                case 'transProject':
                    $project = Project::find($obj);
                    $changed->project = $project->projectName;
                    break;
                default:
                    # code...
                    break;
            };
        }
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $changed));
    }

    public function getApprovalByStatus($status)
    {
        $st = explode(',',$status);
        $query = Approval::latest()->leftJoin('masterproject', 'masterproject.rowID', '=', 'approval.projectID')->leftJoin('transaction', 'transaction.rowID', '=', 'approval.transID');
        if(in_array(Auth::user()->getRole(), [2]))$query->where('requestor',Auth::user()->getId());
        return $query->whereIn('status', $st)->select('approval.*', 'masterproject.projectName as project', 'transaction.tansDesc as transaksi')->get()->toArray();
    }

    public function getTrans(Request $req)
    {
 
        $id = $req->get('id');
        $data = DB::table('transaction')        
        ->leftJoin('client', 'client.rowID', '=', 'transaction.transClientID')
        ->leftJoin('rekening', 'rekening.norek', '=', 'transaction.transRek')
        ->leftJoin('masterproject', 'masterproject.rowID', '=', 'transaction.transProject') 
        ->leftJoin('approval', 'approval.transID', '=', 'transaction.rowID')    
        ->leftJoin('akun', 'akun.idAkun', '=', 'transaction.transAkunID')    
        ->select('transaction.*', 'client.clientName','rekening.*','masterproject.*','approval.*','akun.*')
        ->where('transaction.rowID',$id)
        ->first();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
          
    }

}
