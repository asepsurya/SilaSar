<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\ResetPassMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  // Tampilkan form login
  public function showLoginForm()
  {
    return view("auth.login"); // Sesuaikan dengan view kamu
  }

  // Proses login
  public function login(Request $request)
  {
    // Validasi input
    $request->validate([
      "email" => "required|string", // Validasi untuk email atau phone
      "password" => "required|string",
    ]);

    // Cek apakah input email atau phone
    $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL)
      ? "email"
      : "phone";

    // Ambil kredensial (email atau phone)
    $credentials = [
      $loginField => $request->email, // Jika input adalah email, cari dengan email, jika phone, cari dengan phone
      "password" => $request->password,
    ];

    // Coba login
    if (Auth::attempt($credentials)) {
      // Login berhasil
      $request->session()->regenerate();
      $user = Auth::user();
      if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
        // Jika role adalah admin atau superadmin, arahkan ke dashboard keuangan
        return redirect()->route('dashboard')->with('success', 'Berhasil Login, Selamat Datang');
      }
      return redirect()->route('index.keuangan')->with('success', 'Berhasil Login, Selamat Datang');
    }

    // Login gagal
    return back()
      ->withErrors([
        "email" => "Email atau nomor telepon dan password salah.",
      ])
      ->withInput();
  }

  public function index(){
    return redirect('/login');
  }

  public function passReset(){
   // Panggil API provinsi
    $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

    if ($response->ok()) {
        $provinces = collect($response->json())->pluck('name');
        $captcha = $provinces->random();
    } else {
        $captcha = 'Indonesia';
    }


    session(['captcha_word' => $captcha]);
    return view('auth.resetpass.index',compact('captcha'));
  }

  public function refreshCaptcha()
  {
       $response = Http::get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

    if ($response->ok()) {
        $provinces = collect($response->json())->pluck('name');
        $captcha = $provinces->random();
    } else {
        $captcha = 'Indonesia';
    }


      session(['captcha_word' => $captcha]);

      return response()->json(['captcha' => $captcha]);
  }

    // Bisa kamu letakkan di atas controller, atau di helper file terpisah
  private  function generateRandomPassword($length = 8) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $password = '';
      for ($i = 0; $i < $length; $i++) {
          $password .= $characters[rand(0, strlen($characters) - 1)];
      }
      return $password;
  }


  public function passResetAction(Request $request)
  {
      $request->validate([
          'email' => 'required|email|exists:users,email',
          'chapta' => 'required'
      ]);

      if (strtolower($request->chapta) !== strtolower(session('captcha_word'))) {
          return back()->withErrors(['chapta' => 'Captcha salah']);
      }

      $password = $this->generateRandomPassword(8);

      $user = User::where('email', $request->email)->first();

      $user->password = Hash::make($password);
      $user->save();

      Mail::to($user->email)->send(new ResetPassMail($user->email, $password));

      return back()->with('success', 'Password baru sudah dikirim ke email Anda.');
  }

  public function passChange(request $request){
    $request->validate([
        'id' => 'required|exists:users,id',
        'password' => 'required|min:8',
    ]);

    $user = User::where('id', $request->id)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    Auth::logout(); // keluar dari session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
  }
}
