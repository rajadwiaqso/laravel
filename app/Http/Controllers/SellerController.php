<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\seller;
use App\Models\SellerForm;
use App\Models\trx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function index(){
        $email = Auth::user()->email;
        $sellers = Seller::where('email', $email)->get();
        $orders = $this->ordersTotal();
        $confirmed = $this->confirmedTotal();
        $done = $this->doneTotal();
        return view('seller.index', compact('sellers', 'orders', 'confirmed', 'done'));
    }
    public function form(){
        return view('seller');
    }
    public function post(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'img' => 'required',
            'message' => 'required'

        ]);
        SellerForm::create($request->all());
        return redirect()->route('index');
    }

    public function ordersView(){
        $orders = trx::where('seller_email', Auth::user()->email)->where('status', 'pending')->get(); 
        return view('seller.orders', compact('orders'));
    }

    public function ordersTotal(){
        $orders = trx::where('seller_email', Auth::user()->email)->where('status', 'pending')->get();
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

    public function ordersAccept($id){
        $trx = trx::where('id', $id)->first();
        $trx->status = 'confirm';
        $trx->save();

        return redirect()->back();
    }

    public function ordersReject($id){
        $trx = trx::where('id', $id)->first();
        $trx->status = 'reject';
        $trx->save();
        
        return redirect()->back();
    }

    public function confirmedView(){
        $confirmed = trx::where('status', 'confirm')->where('seller_email', Auth::user()->email)->get();
        return view('seller.confirmed', compact('confirmed'));
    }   

    public function doneView(){
        $done = trx::where('seller_email', Auth::user()->email)->where('status', 'done')->get();
        return view('seller.done', compact('done'));
    }

  
}
