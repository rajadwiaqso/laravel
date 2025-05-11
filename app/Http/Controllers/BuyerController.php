<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\seller;
use App\Models\trx;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
            return redirect()->back()->with('success', 'Profile updated successfully.');
        }
        else{
            return redirect()->route(404);
        }
    }
    public function index(){
       
        $users = User::all();
        // $products = Product::all()->pluck('category')->unique();
        
        $categories = Category::all(); // Ambil semua kategori untuk "Kategori Pilihan"
        $featuredProducts = Product::orderBy('sold', 'desc')->limit(6)->get(); // Ambil 6 produk dengan penjualan terbanyak


        if(Auth::check()){

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.index');
        }
        else if (Auth::user()->role == 'seller'){
            return redirect()->route('seller.index');
        }
    }

         // Tampilkan halaman utama dengan kategori dan produk unggulan
        
        return view('index', compact('users', 'categories', 'featuredProducts'));
    }
    public function signUpView(){
        return view('signup');
    }
  
public function signUpPost(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => ['required', 'email', 'max:255'], // Validasi email
        'name' => 'required', 
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->with('failed', 'Invalid input. Please try again.');
    }

    // Cek apakah email sudah terdaftar
    $existingUser = User::where('email', $request->email)->first();

    if ($existingUser) {
        if (!$existingUser->is_verified) {
            // Jika email belum diverifikasi, arahkan ke halaman verifikasi
             Auth::login($existingUser);
            return redirect()->route('verify.view')->with('warning', 'Your email is not verified. Please verify your email.');
        } else {
            // Jika email sudah diverifikasi, kembalikan dengan pesan error
            return redirect()->back()->with('failed', 'Email is already registered and verified.');
        }
    }

    // Generate kode verifikasi
    $verificationCode = rand(100000, 999999);

    // Simpan user ke database
    $users = new User();
    $users->name = $request->name;
    $users->email = $request->email;
    $users->password = bcrypt($request->password);
    $users->role = 'buyer';
    $users->verification_code = $verificationCode; // Simpan kode verifikasi
    $users->is_verified = false; // Set status verifikasi ke false
    $users->save();

    // Kirim email verifikasi
    Mail::to($users->email)->send(new VerificationMail($verificationCode));

    Auth::login($users);
    // Redirect to verification page with success message
    

    return redirect()->route('verify.view')->with('success', 'Registration successful. Please check your email for the verification code.');
}
public function verify()
{
    if (Auth::user()->is_verified == true){
        return redirect()->route('index');
    }
    return view('verify');
}

public function verifyEmail(Request $request)
{
    $request->validate([
        'verification_code' => 'required',
    ]);

    $user = User::where('email', Auth::user()->email)
                ->where('verification_code', $request->verification_code)
                ->first();

    if (!$user) {
        return redirect()->back()->with('failed', 'Invalid verification code.');
    }

    $user->is_verified = true; // Tandai user sebagai terverifikasi
    $user->verification_code = ""; // Hapus kode verifikasi
    $user->save();

    return redirect()->route('signin.view')->with('success', 'Email verified successfully. You can now log in.');
}


    public function signInView(){
        return view('signin');
    }
    public function signInPost(Request $request)
{
    $data = $request->only('email', 'password');

    // Cek apakah email dan password cocok
    if (Auth::attempt($data)) {
        $request->session()->regenerate();

        // Cek apakah user sudah diverifikasi
        if (!Auth::user()->is_verified) {
            
            return redirect()->route('verify.view')->with('warning', 'Your email is not verified. Please verify your email.');
        }

        // Jika sudah diverifikasi, arahkan ke halaman utama

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin.index');
        }
        else if (Auth::user()->role == 'seller'){
            return redirect()->route('seller.index');
        }
        else{
            return redirect()->route('index');
        }
    }

    // Jika email atau password salah
    return redirect()->back()->with('failed', 'Email or Password is Invalid');
}

public function checkSaldo()
{
    $username = env('API_DIGI_USERNAME');
    $apiKey = env('API_DIGI_KEY');
    $endpoint = env('SALDO_ENDPOINT');

    $signature = md5($username . $apiKey . "depo");

    $response = Http::post($endpoint, [
        'cmd' => 'deposit',
        'username' => $username,
        'sign' => $signature,
    ]);

    if ($response->successful()) {
        return response()->json($response->json());
    }

    return response()->json(['error' => 'Failed to fetch saldo'], 500);
}

public function resetPasswordView()
{
    return view('password_reset');
}

public function sendResetOtp(Request $request)
{
    
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return redirect()->back()->with('failed', 'Email not found.');
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $user->verification_code = $otp;
    $user->save();


    session(['email' => $request->email]); 

    // Send OTP via email
    Mail::to($user->email)->send(new VerificationMail($otp));

    return redirect()->route('password.reset.view')->with('success', 'OTP sent to your email.');
}

public function resetPassword(Request $request)
{
    $request->validate([
        'verification_code' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('email', session('email'))
                ->where('verification_code', $request->verification_code)
                ->first();
                

    if (!$user) {
        
        return redirect()->back()->with('failed', 'Invalid OTP or email.');
    }

    // Reset password
    $user->password = Hash::make($request->password);
    $user->verification_code = ""; // Clear OTP
    $user->save();
    session()->forget('email'); // Clear session email

    return redirect()->route('signin.view')->with('success', 'Password reset successfully. You can now log in.');
}

public function changePassword(Request $request, $id){
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required',
    ]);
    $user = User::find($id);
    
    if (Hash::check($request->current_password, $user->password)) {
        // Passwords match, update the password
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->back()->with('success', 'Password changed successfully.');
    } else {
        // Passwords do not match
        return redirect()->back()->with('failed', 'Current password is incorrect.');
    }
}


    public function logout(){
        Auth::logout();
        return redirect()->route('signin.view');
    }

    public function orders(Request $request){
    
        $perPage = $request->input('perPage',10);
        $orders = trx::where('buyer_email', Auth::user()->email)->latest()->paginate($perPage);
        
       $i = 0;
       
        foreach ($orders as $order) {
            
            $seller = User::where('email', $order['seller_email'])->first();
            $orders[$i]['name'] = $seller['name'];
            $i++;
            
        }

        return view('orders', compact('orders'));
    }

    public function ordersReject(Request $request, $id){
        $trx = trx::where('trx_id', $id)->first();
        $status_date = $trx->status_date;
        $status_date['reject'] = now()->format('Y-m-d H:i:s');
        $status_date['from'] = Auth::user()->email;
        $status_date['reason'] = $request->reason;
        $status_date['message'] = $request->message;
        $trx->status_date = $status_date;
        $trx->save();
         
        return redirect()->back();
    }


    public function confirm($id){
        $trx = trx::where('id', $id)->first();
        // 

        $products = Product::where('id', $trx->id_product)->first();
        $products->sold += 1;
        $products->save();

        // 
        $seller = seller::where('email', $trx->seller_email)->first();
        $seller->credits += $trx->price;
        $seller->diproses -= $trx->price;
        $seller->save();

        // 
        $trx->status = 'done';
        $status_date = $trx->status_date;
        $status_date['done'] = now()->format('Y-m-d H:i:s');
        $trx->status_date = $status_date;

        $trx->save();

        return redirect()->back();
    }
    
}
