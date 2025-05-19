<?php

namespace App\Http\Controllers;

use App\Models\seller;
use App\Models\SellerForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(){
        $forms = SellerForm::all();
        return view('admin.index', compact('forms'));
    }
    public function accept($id){
        $form = SellerForm::find($id);

       
        $user = User::where('email', $form->from)->first();
        $user->is_seller = true;
        $user->save();
        $form->delete();
        Storage::delete('images/form/' . $form->img);

        $seller = new seller();
        $seller->name = $form->name;
        $seller->email = $form->from;
        $seller->profile_picture = 'default.jpg';
        $seller->credits = 0;
        $seller->sold_total = 0;
        $seller->product_total = 0;
        $seller->diproses = 0;
        $seller->save(); 

        

        return redirect()->back();
    }
    public function decline($id){
        $form = SellerForm::find($id);
        
        $form->delete();
        Storage::delete('images/form/' . $form->img);

        return redirect()->back();
    }
}
