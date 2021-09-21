<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Library\Services\Shared;
use App\Library\Model\Model;
use Illuminate\Support\Facades\Hash;
use App\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
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
        return view('product.index');
    }

    public function getData(Request $request)
    {
        $data = Product::latest()->orderByDesc('created_date')->get();
        return Datatables::of($data)               
            ->make(true);
    }

    public function edit(Request $req)
    {
        $id = $req->get('id');
        $data = Product::find($id)->toArray();
        echo json_encode(array('status' => 200, 'message' => 'Process Succesfully', 'data' => $data));
    }

    public function crud(ProductRequest $request)
    {
        try{
            $product = new Product;
            if($request->input('id') != ''){
                $product = Product::find($request->input('id'));
                $product->updated_by = Auth::user()->getUsername();
            }
            $product->name_id = $request->input('name_id');
            $product->name_en = $request->input('name_en');
            $harga = str_replace(".","",$request->input('harga'));
            $product->harga =  (int)$harga;
            if($request->input('harga_discount')!=''){
                $harga_discount = str_replace(".","",$request->input('harga_discount'));
                $product->harga_discount = (int)$harga_discount;
            }
            $product->satuan = $request->input('satuan');
            $product->ukuran = $request->input('ukuran');
            $product->kategori = $request->input('kategori');
            $product->stock = $request->input('stock'); 
            $product->desc = $request->input('desc');     
            $product->is_active = $request->input('is_active');         
            if($request->input('id') == ''){                                
                $product->created_by = Auth::user()->getUsername();
            }            

            if($request->input('uploaded') == 0 || $request->file('photo')){
                $file = $request->file('photo');
                $path = public_path('images/products');
                $imageName = time().'.'.$file->getClientOriginalExtension(); 
                $fileName = '/images/products/'. $imageName;
                $file->move($path, $imageName);
                $product->path_photo = $fileName; 
            }
            
            if($product->save()){   
                $type = ($request->input('id') == '')?' save':' update';             
                $msg = Auth::user()->getUsername(). $type.' product succesfully : '.json_encode($product);              
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
            $data = Product::whereIn('id', $id);            
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

}
