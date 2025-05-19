<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\seller;
use App\Models\SellerForm;
use App\Models\trx;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function index(){
        $email = Auth::user()->email;
        $seller = Seller::where('email', $email)->first();
        if (!$seller) {
        // Bisa redirect ke halaman lain atau tampilkan pesan error
        abort(404, 'Seller data not found. Silakan lengkapi data seller Anda.');
    }
        $orders = $this->ordersTotal();
        $confirmed = $this->confirmedTotal();
        $done = $this->doneTotal();
        $reject = $this->rejectTotal();

        $totals = trx::where('seller_email', Auth::user()->email)->get();
        $total = count($totals);
    
        // Data untuk grafik penjualan
        $sales = trx::where('seller_email', $email)
                    ->where('status', 'done')
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();
    
        $salesData = [
            'labels' => $sales->pluck('date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d M'); // Format tanggal
            })->toArray(),
            'data' => $sales->pluck('total')->toArray()
        ];

            //    session(['user_type' => 'seller']);
    
        return view('seller.index', compact('total','seller', 'orders', 'confirmed', 'done', 'reject', 'salesData'));
        
    }

    public function profileView($store_name){
    $seller = seller::where('name', $store_name)->first();
    if (is_null($seller)) {
        abort(404, 'Seller not found');
    }
    $user = User::where('email', $seller->email)->first();
    if (is_null($user)){
        abort(404, 'User not found');
    }
    $products = Product::where('store_name', $store_name)->get();
    return view('seller.profile', compact('seller', 'user', 'products'));
}

   public function updateProfilePicture(Request $request, $id)
    {
        $user = Seller::find($id);

        if ($id ==  $user->id){
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            // dd($request->profile_picture);
            // Hapus gambar lama jika ada
            if ($user->profile_picture != 'default.jpg') {
                Storage::delete('images/profile/' . $user->profile_picture);
            }

            // Simpan gambar baru
            $imageName = Auth::user()->username . '_' . time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('images/profile', $imageName);

            // dd($imageName);

            // Update nama gambar di database
            $user->profile_picture = $imageName;
            $user->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }
        else{
            return redirect()->route(404);
        }
    }
  

    public function form(){
        $form = SellerForm::where('from', Auth::user()->email)->first();
        if (is_null($form)){
            $data = 0;
        }else{
            $data = 1;
        }

        return view('seller', compact('data'));
    }
    
   public function post(Request $request)
{
     $validated = $request->validate([
        'fullname' => 'required|string|max:255',
        'name'     => [
            'required',
            'string',
            'max:255',
            // Pastikan nama toko unik di seller_forms dan sellers
            function ($attribute, $value, $fail) {
                if (\App\Models\SellerForm::where('name', $value)->exists() ||
                    \App\Models\seller::where('name', $value)->exists()) {
                    $fail('Nama toko sudah digunakan. Silakan pilih nama lain.');
                }
            }
        ],
        'phone'    => 'required|string|max:20',
        'ktp'      => 'required|boolean',
        'nik'      => 'nullable|required_if:ktp,1|string|max:30',
        'img'      => 'nullable|required_if:ktp,1|image|mimes:jpg,jpeg,png|max:2048',
        'message'  => 'nullable|string',
    ]);

    $form = new SellerForm();
    $form->fullname = $validated['fullname'];
    $form->name = $validated['name'];
    $form->phone = $validated['phone'];
    $form->ktp = $validated['ktp'];
    $form->nik = $validated['ktp'] ? $validated['nik'] : null;
    $form->message = $validated['message'] ?? null;
    $form->from = Auth::user()->email;

    // Handle file upload jika ada
    if ($request->hasFile('img') && $validated['ktp']) {
        $fname = uniqid() . '.' . $request->img->extension();
        $request->img->storeAs('images/form', $fname, 'public');
        $form->img = $fname;
    } else {
        $form->img = null;
    }

    $form->save();

    return redirect()->route('index');
}

    public function ordersView(Request $request){
        $perPage = $request->input('perPage', 10);

        $orders = trx::where('seller_email', Auth::user()->email)->where('status', 'pending')->whereRaw("JSON_EXTRACT(status_date, '$.from') IS NULL")->latest()->paginate($perPage); 

        $i = 0;
       
        foreach ($orders as $order) {
            
            $seller = User::where('email', $order['buyer_email'])->first();
            $orders[$i]['name'] = $seller['name'];
            $i++;
            
        }
        return view('seller.orders', compact('orders'));
    }
    public function ordersAllView(Request $request){
        $perPage = $request->input('perPage', 10);

        $orders = trx::where('seller_email', Auth::user()->email)->latest()->paginate($perPage); 

        $i = 0;
       
        foreach ($orders as $order) {
            
            $seller = User::where('email', $order['buyer_email'])->first();
            $orders[$i]['name'] = $seller['name'];
            $i++;
            
        }
        return view('seller.orders', compact('orders'));
    }

    public function ordersTotal(){
        $orders = trx::where('seller_email', Auth::user()->email)->where('status', 'pending')->whereRaw("JSON_EXTRACT(status_date, '$.from') IS NULL")->get();
        $total = count($orders);
        return $total;
    }

    public function confirmedTotal (){
        $confirmed = trx::where('seller_email', Auth::user()->email)->where('status', 'confirm')->get();
        $total = count($confirmed);
        return $total;
    }

    public function doneTotal(){
        $done = trx::where('seller_email', Auth::user()->email)->where('status', 'done')->get();
        $total = count($done);
        return $total;
    }
    public function rejectTotal(){
        $done = trx::where('seller_email', Auth::user()->email)->where('status', 'reject')->get();
     


        $reject = trx::where('seller_email', Auth::user()->email)->whereNotNull('status_date->from')
        ->get();


        $total = count($done) + count($reject);
        return $total;
    }

    public function ordersAccept($id){
        $trx = Trx::where('id', $id)->first();
        $seller = seller::where('email', Auth::user()->email)->first();

    if ($trx) {
        $existingStatusDate = $trx->status_date;

        // Pastikan $existingStatusDate adalah string dan tidak kosong
        if (is_string($existingStatusDate) && !empty($existingStatusDate)) {
            $decodedStatusDate = json_decode($existingStatusDate, true);
            if (!is_array($decodedStatusDate)) {
                $decodedStatusDate = [];
            }
        }

        $existingStatusDate['confirm'] = Carbon::now()->toDateTimeString();
        $trx->status_date = $existingStatusDate;
        $trx->status = 'confirm';
        $trx->save();

        $seller->diproses += $trx->total;   
        $seller->save();

        return redirect()->back();
    }

    abort(404, 'Pesanan tidak ditemukan.');
    
    }

    public function ordersReject(Request $request,$id){
        $trx = trx::where('id', $id)->first();
        $trx->status = 'reject';
        $status_date = $trx->status_date;
        $status_date['reason'] = $request->reason;
        $status_date['message'] = $request->message;
        

        $status_date['reject'] = now()->format('Y-m-d H:i:s');
        $trx->status_date = $status_date;
      
        $trx->save();
        
        return redirect()->back();
    }

    public function confirmedView(Request $request){
        $perPage = $request->input('perPage', 10);
        $confirmed = trx::where('status', 'confirm')->where('seller_email', Auth::user()->email)->latest()->paginate($perPage);
        $i = 0;
       
        foreach ($confirmed as $order) {
            
            $seller = User::where('email', $order['buyer_email'])->first();
            $confirmed[$i]['name'] = $seller['name'];
            $i++;
            
        }
        return view('seller.confirmed', compact('confirmed'));
    }   

    public function doneView(Request $request){
        
        $perPage = $request->input('perPage', 10); 
        $done = trx::where('seller_email', Auth::user()->email)->where('status', 'done')->latest()->paginate($perPage);
        $i = 0;
       
        foreach ($done as $order) {
            
            $seller = User::where('email', $order['buyer_email'])->first();
            $done[$i]['name'] = $seller['name'];
            $i++;
            
        }
        return view('seller.done', compact('done'));
    }

    public function rejectView(Request $request){
        $perPageSeller = $request->input('perPageSeller', 10); // Default 10 per halaman untuk penjual
        $perPageBuyer = $request->input('perPageBuyer', 10); // Default 10 per halaman untuk pembeli
    
        $done = trx::where('seller_email', Auth::user()->email)->where('status', 'reject')->latest()->paginate($perPageSeller);

        $reject = trx::where('seller_email', Auth::user()->email)->whereNotNull('status_date->from')
        ->latest()
        ->paginate($perPageBuyer);
        
        $i = $j = 0;

        foreach ($reject as $order) {
            
            $sellers = User::where('email', $order['buyer_email'])->first();
            $reject[$j]['name'] = $sellers['name'];
            $j++;
            
        }
       
        foreach ($done as $order) {
            
            $seller = User::where('email', $order['buyer_email'])->first();
            $done[$i]['name'] = $seller['name'];
            $i++;
            
        }

        
        return view('seller.reject', compact('done', 'reject'));
    }

    public function terimaSaran(Request $request,$id){

        $trx = trx::where('trx_id', $id)->first();
        $trx->status = 'reject';
        $status_date = $trx->status_date;
        $status_date['seller_message'] = $request->message;
        $status_date['reject_seller'] = now()->format('Y-m-d H:i:s'); 
        $trx->status_date = $status_date;
        $trx->save();

        return redirect()->back();
        
    }

    public function tolakSaran(Request $request, $id){
        $trx = trx::where('trx_id', $id)->first();
        $trx->status = 'confirm';
        $status_date = $trx->status_date;
        $status_date['confirm'] = now()->format('Y-m-d H:i:s');
        $status_date['seller_message'] = $request->message;
        $trx->status_date = $status_date;
        $trx->save();

        return redirect()->back();
    }

  
}
