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
        $user = new User();
        $user->name = $form->name;
        $user->email = $form->email;
        $user->role = 'seller';
        $user->password = bcrypt('rajaa');
        $user->save();
        $form->delete();
        Storage::delete('images/form/' . $form->img);

        $seller = new seller();
        $seller->name = $form->name;
        $seller->email = $form->email;
        $seller->credits = 0;
        $seller->sold_total = 0;
        $seller->product_total = 0;
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
