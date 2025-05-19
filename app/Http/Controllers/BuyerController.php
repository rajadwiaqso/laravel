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
// use Illuminate\Support\Facades\Mail;
// use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Services\EmailApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BuyerController extends Controller
{
    protected $emailApiService;

    public function __construct(EmailApiService $emailApiService)
    {
        $this->emailApiService = $emailApiService;
    }

    public function profileView($username){
        $user = User::where('username', $username)->first();
        if (!$user) {
            return redirect()->route('index')->with('failed', 'User not found.');
        }
        return view('users.profile', compact('user'));
    }


    public function profile(){
        $seller = seller::where('email', Auth::user()->email)->first();
        return view('profile', compact('seller'));
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

    public function updateProfilePicture(Request $request, $id)
    {
        $user = User::find($id);

        if (Auth::user()->id ==  $user->id){
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

            //   session(['user_type' => 'buyer']);
        
        return view('index', compact('users', 'categories', 'featuredProducts'));
    }
    public function signUpView(){
        return view('signup');
    }
  
public function signUpPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'name' => 'required',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($request->id),

            ],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('failed', 'Username or Email already taken');
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if (!$existingUser->is_verified) {
                Auth::login($existingUser);
                return redirect()->route('verify.view')->with('warning', 'Your email is not verified. Please verify your email.');
            } else {
                return redirect()->back()->with('failed', 'Email is already registered and verified.');
            }
        }

        $verificationCode = rand(100000, 999999);

        $users = new User();
        $users->name = $request->name;
        $users->username = $request->username;
        $users->profile_picture = 'default.jpg';
        $users->email = $request->email;
        $users->password = bcrypt($request->password);
        $users->role = 'buyer';
        $users->verification_code = $verificationCode;
        $users->is_verified = false;
        $users->save();

        Auth::login($users);

           
    session(['otp_last_sent' => now()]);

        // Kirim email verifikasi menggunakan EmailApiService
        $subject = 'Verifikasi Email Anda';
        $body = view('emails.emails', ['verificationCode' => $verificationCode])->render(); // Render view email
        $fromEmail = env('MAIL_FROM_ADDRESS'); // Ambil alamat pengirim dari .env
        $fromName = env('APP_NAME'); // Ambil nama aplikasi dari .env (opsional, bisa dikonfigurasi di service)

        $this->emailApiService->sendEmail($users->email, $subject, $body, $fromEmail, $fromName);

        return redirect()->route('verify.view')->with('success', 'Registration successful. Please check your email for the verification code.');
    }

    public function resendVerify(Request $request)
{
    


    $user = User::where('email', Auth::user()->email)->first();

    $lastSent = session('otp_last_sent');

     if ($lastSent) {
    $currentTime = now();
    $remainingSeconds = $currentTime->diffInSeconds(Carbon::parse($lastSent)); 

    $remainingSeconds = abs($remainingSeconds);
  



    if ($remainingSeconds < 60) {
        $wait = 60 - $remainingSeconds;
        $wait = ceil($wait); // Membulatkan ke atas
        return redirect()->back()->with('warning', 'Harap tunggu ' . $wait . ' detik...')->with('wait_time', $wait);
    }
}



    if (!$user) {
        return redirect()->back()->with('failed', 'Email not found.');
    }

    // Generate new verification code
    $verificationCode = rand(100000, 999999);
    $user->verification_code = $verificationCode;
    $user->save();

    session(['otp_last_sent' => now()]);

    // Send verification email again
    $subject = 'Verifikasi Email Anda';
    $body = view('emails.emails', ['verificationCode' => $verificationCode])->render(); // Render view email
    $fromEmail = env('MAIL_FROM_ADDRESS'); // Ambil alamat pengirim dari .env
    $fromName = env('APP_NAME'); // Ambil nama aplikasi dari .env (opsional, bisa dikonfigurasi di service)
    
    $this->emailApiService->sendEmail($user->email, $subject, $body, $fromEmail, $fromName);



    return redirect()->route('verify.view')->with('success', 'Verification code sent to your email.');
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
        else{
            return redirect()->route('choose');
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
    session(['otp_last_sent' => now()]);

    // Send OTP via email
    
    $subject = 'Password Reset OTP';
    $body = view('emails.emails', ['verificationCode' => $otp])->render(); // Render view email
    $fromEmail = env('MAIL_FROM_ADDRESS'); // Ambil alamat pengirim dari .env
    $fromName = env('APP_NAME'); // Ambil nama aplikasi dari .env (opsional, bisa dikonfigurasi di service)
    $this->emailApiService->sendEmail($request->email, $subject, $body, $fromEmail, $fromName);

    return redirect()->route('password.reset.view')->with('success', 'OTP sent to your email.');
}

public function sendResetOtpAgain(Request $request)
{
    $email = session('email');
    $lastSent = session('otp_last_sent');
    $now = now();
  

// dd($lastSent, $now, $now->diffInSeconds(($lastSent)));

// dd($lastSent, $now, $now->diffInSeconds($lastSent));



    // if ($lastSent) {
    //     $diffInSeconds = $now->diffInSeconds(Carbon::parse($lastSent));
    //     if ($diffInSeconds < 60) {
    //         // ... tampilkan pesan tunggu ...
    //         dd($diffInSeconds);
    //     }
    // }

    if ($lastSent) {
    $currentTime = now();
    $remainingSeconds = $currentTime->diffInSeconds(Carbon::parse($lastSent)); 

    $remainingSeconds = abs($remainingSeconds);
  



    if ($remainingSeconds < 60) {
        $wait = 60 - $remainingSeconds;
        $wait = ceil($wait); // Membulatkan ke atas
        return redirect()->back()->with('warning', 'Harap tunggu ' . $wait . ' detik...')->with('wait_time', $wait);
    }
}


    // Cek apakah sudah 60 detik sejak pengiriman terakhir
    // if ($lastSent && $now->diffInSeconds(Carbon::parse($lastSent)) < 60) {
    //     $wait = 60 - $now->diffInSeconds($lastSent);
    //     $wait = ceil($wait); // Membulatkan ke atas
    //      return redirect()->back()->with('failed', 'Harap tunggu ' . (60 - $now->diffInSeconds($lastSent)) . ' detik sebelum mengirim ulang OTP.');
    // }
   

    $user = User::where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->with('failed', 'Email not found.');
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $user->verification_code = $otp;
    $user->save();

    session(['otp_last_sent' => now()]); // Update waktu pengiriman OTP

    // Send OTP via email
    $subject = 'Password Reset OTP';
    $body = view('emails.emails', ['verificationCode' => $otp])->render();
    $fromEmail = env('MAIL_FROM_ADDRESS');
    $fromName = env('APP_NAME');
    $this->emailApiService->sendEmail($email, $subject, $body, $fromEmail, $fromName);

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
            
            $seller = Seller::where('email', $order['seller_email'])->first();
            $product = Product::where('id', $order['id_product'])->first();
            $orders[$i]['product'] = $product['produk_name'];
            $orders[$i]['category'] = $product['category'];
            $orders[$i]['store'] = $order['store_name'];
            $orders[$i]['id'] = $product['id'];
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
        $products->sold += $trx->quantity;
        $products->save();

        // 
        $seller = seller::where('email', $trx->seller_email)->first();
        $seller->credits += $trx->total;
        $seller->diproses -= $trx->total;
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
