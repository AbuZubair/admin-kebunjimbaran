<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {        
        $query = DB::table('product')
                ->leftJoin(DB::raw('(SELECT COUNT(*) AS total, AVG(rate) AS rating, product_id 
                FROM rating GROUP BY product_id) b') , 'product.id', '=', 'b.product_id');

        if(isset($request->category)){
            $query = $query->where('kategori',$request->category);
        }
        
        if(isset($request->search)){
            if($request->lang == 'id')$query = $query->where('name_id','like', '%'.$request->search.'%');
            else $query = $query->where('name_en','like', '%'.$request->search.'%');
        }

        $query = $query->where('is_active','Y')->orderBy('updated_date', 'DESC');
        
        $data = $query->paginate($request->offset);
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => $data
        );
        return response()->json($resp, 200);
    }

    public function getById(Request $request)
    {
        $product_id = explode(',',$request->product_id);
        $product = Product::whereIn('id',$product_id)->get()->toArray();
        
        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => $product
        );
        return response()->json($resp, 200);
    }

    public function rating(Request $request)
    {
        $data = $request->input('data');
        $order = $request->input('order');

        for ($i=0; $i < count($data); $i++) { 
            if(in_array(1, $data[$i]['rate']) === true){
                $r = array_filter($data[$i]['rate'],function($element) {
                    if($element==1)return $element;
                });
                $rate = array(
                    'product_id' => $data[$i]['id'],
                    'rate' => count($r),
                    'phone_no' => $order['phone']
                );
                DB::table('rating')->insert($rate);
            }
        }

        $resp = array(
            'status' => true,
            'message' => 'Succesfully',
            'data' => []
        );
        return response()->json($resp, 200);
    }

    // public function store(Request $request)
    // {
    //     $product = Product::create($request->all());

    //     return response()->json($product, 201);
    // }

    // public function update(Request $request, Product $product)
    // {
    //     $product->update($request->all());

    //     return response()->json($product, 200);
    // }

    // public function delete(Product $product)
    // {
    //     $product->delete();

    //     return response()->json(null, 204);
    // }

}
