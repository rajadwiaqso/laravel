<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\seller;
use App\Models\trx;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   

    public function index($category){
        $products = Product::where('category', $category)->get();
        return view('products', compact('products'));
    }


     public function all(){
        $products = Product::all();
        return compact('products');
    }

    public function create(){
        return view('seller.create');
    }

    public function createSubmit(Request $request){
        
        $request->validate([
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'description' => 'required',
            'image' => 'required',
            'category' => 'required'
        ]);



        $imageName = time() . '.' . $request->image->extension();

        $request->image->storeAs('images', $imageName);
      

            $product = new Product();
            $product->produk_name = $request->product_name;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->description = $request->description;
            $product->img = $imageName;
            $product->category = $request->category;
            $product->store_name = Auth::user()->name;
            $product->save();
            $this->totalProduct();
            
            return redirect()->route('seller.index');


       
    }

    public function list(){
        $name = Auth::user()->name;
        $list = Product::where('store_name', $name)->get();
        return view('seller.list', compact('list'));
    }

    public function delete($id){
        $product = Product::find($id);
        $product->delete();
        $this->totalProduct();
        return redirect()->back();
    }

    public function edit($id){
        $product = Product::find($id);
        return view('seller.edit', compact('product'));
    }

    public function editSubmit(Request $request, $id){
        $product = Product::find($id);
        $product->produk_name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        if (is_null($request->img)) {
            $product->img = $product->img;
        }else{
            Storage::delete('images', $product->img);

            $imageName = time() . '.' . $request->img->extension();

            $request->img->storeAs('images', $imageName);
            $product->img = $imageName;
        }
         
        $product->description = $request->description;
        $product->save();
        return redirect()->route('index'); 
    }
    
    public function totalProduct(){
        $name = Auth::user()->name;
        $products = Product::where('store_name', $name)->get();
        $total = Seller::where('email', Auth::user()->email)->first();
        $total->product_total = count($products);
        $total->save(); 
    }

    public function details($category, $store, $product){
        $product = Product::where('category', $category)->where('store_name', $store)->where('produk_name', $product)->first();
        if(is_null($product)){
            return redirect()->back();
        }
        return view('products.details', compact('product'));
    }

    public function buy($category, $store, $product){
        $data = Product::where('category', $category)->where('produk_name', $product)->where('store_name', $store)->first();
        $seller = seller::where('name', $store)->first();
        $trx = new trx();
        $trx->buyer_email = Auth::user()->email;
        $trx->seller_email = $seller->email;
        $trx->product = $data->produk_name;
        $trx->price = $data->price;
        $trx->category = $data->category;
        $trx->status = 'pending';
        $id = $data->id . $seller->id . $trx->id . uniqid();
        $trx->trx_id = "TRX_$id";
        $trx->save(); 

        $data->stock--;
        $data->save();

        $seller->sold_total++;
        $seller->credits += $data->price;
        $seller->save();

        return redirect()->route('index');
    }
}
