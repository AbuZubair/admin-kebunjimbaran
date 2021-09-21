<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Param;

class ParamController extends Controller
{
 
    public function fetch(Request $request)
    {
        $data = Param::where('param',$request->param)->where('is_active', 'Y')->get()->toArray();
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => $data
        );
        return response()->json($resp, 200);
    }


}
