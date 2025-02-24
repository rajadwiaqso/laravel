<?php

namespace App\Http\Controllers;

use App\Models\Mong;
use MongoDB\Driver\Manager;

use MongoDB; // 

use Illuminate\Http\Request;

class Mongo extends Controller
{
    public function index(){
        
        $user = Mong::all();
        return view('mongo', compact('user'));
    }
}
