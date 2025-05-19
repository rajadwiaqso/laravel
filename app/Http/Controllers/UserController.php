<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\SellerController;
use App\Models\Product;
use App\Models\seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

     protected $buyerController;
    protected $sellerController;

    public function __construct(BuyerController $buyerController, SellerController $sellerController)
    {
        $this->buyerController = $buyerController;
        $this->sellerController = $sellerController;
    }
    
   
    public function index(){
        return view('users.choose');
        
    }

    
    public function chooseBuyer(){
   
        $user = User::where('email', Auth::user()->email)->first(); 
        $user->role = 'buyer';
        $user->save();

        return redirect()->route('index');
    }
    public function chooseSeller(){

        $user = User::where('email', Auth::user()->email)->first();
        if($user->is_seller == 1){
            $user->role = 'seller';
            $user->save();

        return redirect()->route('seller.index');
        }   
        else{
            return redirect()->route('index')->with('error', 'You are not a seller');
        }
 
    }


}
