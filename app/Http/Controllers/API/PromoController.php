<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Promo;

class PromoController extends Controller
{
    public function index(Request $request)
    {        
        $query = DB::table('product')
                ->leftJoin(DB::raw('(SELECT COUNT(*) AS total, AVG(rate) AS rating, product_id 
                FROM rating WHERE product_id=1 GROUP BY product_id) b') , 'product.id', '=', 'b.product_id');

        if(isset($request->category)){
            $query = $query->where('kategori',$request->category);
        }
        
        if(isset($request->search)){
            if($request->lang == 'id')$query = $query->where('name_id','like', '%'.$request->search.'%');
            else $query = $query->where('name_en','like', '%'.$request->search.'%');
        }

        $data = $query->paginate($request->offset);
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => $data
        );
        return response()->json($resp, 200);
    }

    public function show(Product $product)
    {
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => $product
        );
        return response()->json(json_encode($resp), 201);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return response()->json($product, 200);
    }

    public function delete(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }

    public function submitVoucher(Request $request)
    {
        $code = $request->input('code');
        $msisdn = $request->input('msisdn');

        $check_reseller = DB::table('tmp_global_reference')->where(array('param' => 'whitelist_vcr', 'ref_value' => $msisdn))->get()->first();
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => null
        );
        if(isset($check_reseller)){
            try {
                $promo = Promo::where(array('kode_promo' => $code, 'is_active' => 'Y'))->get()->first();
               
                if(isset($promo)){
                    $resp['data'] = $promo->toArray();
                }
                return response()->json($resp, 200);
            } catch (\Throwable $th) {
                $resp['status'] = false;
                $resp['message'] = $th['message'];
                return response()->json($resp, 500);
            }
        }else{
            $resp['status'] = false;
            $resp['message'] = 'Unidentified';
            return response()->json($resp, 200);
        }

        
    }

}
