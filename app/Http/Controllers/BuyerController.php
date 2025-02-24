<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\trx;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BuyerController extends Controller
{
    public function profile(){
        return view('profile');
    }
    // public function updateProfile(Request $request, User $users){
    //     
    //     $user = User::find($users);
    //     $user->name = $request->name;
    //     $user->password = bcrypt($request->password);
    //     $user->save();
    //     return redirect()->route('index');
    // }
    public function updateProfile(Request $request, $id){

        $user = User::find($id);
        if (Auth::user()->id ==  $user->id){
            $user->name = $request->name;
            $user->save();
            return redirect()->route('index');
        }
        else{
            return redirect()->route(404);
        }
    }
    public function index(){
        $users = User::all();
        $products = Product::all()->pluck('category')->unique();
        return view('index', compact('users', 'products'));
    }
    public function signUpView(){
        return view('signup');
    }
    public function signUpPost(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255', Rule::unique('users')], // Validasi email unik
            'name' => 'required', 
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('failed', 'Email Already Registered');
        }

       $users = new User();
       $users->name = $request->name;
       $users->email = $request->email;
       $users->password = bcrypt($request->password);
       $users->role = 'buyer';
       $users->save();

       return redirect()->route('signin.view');
    }
    public function signInView(){
        return view('signin');
    }
    public function signInPost(Request $request){
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            $request->session()->regenerate();
            return redirect()->route('index');    
        }
        return redirect()->back()->with('failed', 'Email or Password is Invalid');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('signin.view');
    }

    public function orders(){
        $orders = trx::where('buyer_email', Auth::user()->email)->get();
        return view('orders', compact('orders'));
    }

    public function confirm($id){
        $trx = trx::where('id', $id)->first();
        $trx->status = 'done';
        $trx->save();

        return redirect()->back();
    }
    
}
