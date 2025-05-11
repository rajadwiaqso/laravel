<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\seller;
use App\Models\trx;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\ProductStockUpdated;

class ProductController extends Controller
{
   

    public function index($category){
        $products = Product::where('category', $category)->get();
        
        return view('products', compact('products', 'category'));
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
            Storage::delete('images/' . $product->img);

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

    public function details($category, $store, $product, $id_product){
        $rating = trx::where('product', $product)
        ->where('status', 'done')
        ->where('id_product', $id_product)
        ->where('category', $category)
        ->latest()->paginate();
        $product = Product::where('category', $category)->where('store_name', $store)->where('id', $id_product)->first();
        
       $i = 0;
        foreach ($rating as $rate) {
            
            $buyers = User::where('email', $rate['buyer_email'])->first();
            $rating[$i]['name'] .= $buyers['name'];
            $i++;
        }
        
        $buyers = 1;
     
        if(is_null($product)){
            return redirect()->back();
        }
        return view('products.details', compact('product', 'rating'));
    }

    public function buy($category, $store, $product, $id){

        if(Auth::guest()){
            return redirect()->route('signin.view');
            
        }

        $data = Product::where('category', $category)->where('produk_name', $product)->where('id', $id)->where('store_name', $store)->first();
        $seller = seller::where('name', $store)->first();
        $trx = new trx();
        $trx->buyer_email = Auth::user()->email;
        $trx->seller_email = $seller->email;
        $trx->product = $data->produk_name;
        $trx->price = $data->price;
        $trx->category = $data->category;
        $trx->status = 'pending';
        $trx->status_date = ['pending' => now()->format('Y-m-d H:i:s')];
        $trx->rating = 0;
        $trx_id = $data->id . $seller->id . $trx->id . uniqid();
        $trx->id_product = $id;
        $trx->trx_id = "TRX_$trx_id";
        $trx->save(); 

        $data->stock--;
        $data->save();

        $seller->sold_total++;
        $seller->save();     

        broadcast(new ProductStockUpdated($data->id, $data->stock))->toOthers();

        return redirect()->route('index');
    }

    public function ratingView($trx){
        $data = trx::where('trx_id', $trx)->first();

        return view('rating', compact('data'));
    }

    public function ratingPost($trx, Request $request){
        $data = trx::where('trx_id', $trx)->first();
        $data->rating = [
            'rating' => $request->rating,
            'message' => $request->message,
            'date' => now()->format('Y-m-d H:i:s')
        ];
        $data->save();
        return redirect()->route('index');
    }

    public function categoryProducts(Category $category)
    {
        $products = Product::where('category', $category->name)->paginate(12); // Contoh pagination
        return view('products.category', compact('category', 'products'));
        
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('index'); // Atau tampilkan semua produk
        }

        if(Auth::check())
{
    if (Auth::user()->role == 'buyer') {
        $products = Product::where('produk_name', 'like', "%$query%")
        ->orWhere('category', 'like', "%$query%")
        ->paginate(12); // Contoh pagination

        return view('products.search_results', compact('products', 'query'));
    } else if (Auth::user()->role == 'seller') {
        
        $products = Product::where('store_name', Auth::user()->name)->where('produk_name', 'like', "%$query%")
        ->orWhere('category', 'like', "%$query%")
        ->paginate(12); // Contoh pagination

        return view('products.search_results', compact('products', 'query'));
    }
    else{
        dd($query); // Atau tampilkan semua produk
    }
}
else{
    $products = Product::where('produk_name', 'like', "%$query%")
    ->orWhere('category', 'like', "%$query%")
    ->paginate(12); // Contoh pagination

return view('products.search_results', compact('products', 'query'));
}
        
    }

}
