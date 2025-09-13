<?php

namespace App\Http\Controllers\Auth;

use Artisan;
use App\Models\App;
use App\Models\ikm;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\AccountActivationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  public function register()
  {
    return view("auth.register");
  }

  public function registerAction(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "name" => "required|string|max:255",
      "phone" => "required|string|max:15|unique:users,phone",
      "email" => "required|email|unique:users,email",
      "password" => "required|string|min:8|same:cpassword",
      
    ]);

    if ($validator->fails()) {
      // Mengirim kembali ke form dengan pesan error
      return back()->withErrors($validator)->withInput();
    }
    $user = $request->name;
    $token = Str::random(64); 

    // Simpan data pengguna ke database
    $user = User::create([
      "name" => $request->name,
      "phone" => $request->phone,
      "email" => $request->email,
      "password" => Hash::make($request->password),
      "role" => "gold",
      "activation_token" => $token,
    ]);

    $user->assignRole('gold');

    $pengguna = ikm::create([
      'nik'=>$request->nik,
      'email'=>$request->email,
      'nama'=>$request->name,
      'telp'=>$request->phone,
    ]);

    App::create([
      'key'=>'default_rekening',
      'value'=>'',
      'auth'=> $user->id
    ]);

    //  Artisan::call('optimize:clear');
    // contoh token
    Mail::to($request->email)->send(new AccountActivationMail($user, $token));
    return redirect()->route('successRegister', ['token' => $token])->with("success", "Pendaftaran Berhasil, Silahkan Lanjutkan ke Tahap Selanjutnya");
  }

  public function checkEmail(request $request){
      $exists = User::where('email', $request->email)->exists();
      return response()->json(['exists' => $exists]);
  }

  public function activate($token)
  {
      $user = User::where('activation_token', $token)->first();

      if (!$user) {
          return redirect('/login')->withErrors('Token tidak valid.');
      }

      $user->email_verified_at = now();
      $user->activation_token = null;
      $user->save();

      Auth::login($user); // 👈 INI WAJIB OBJECT User
      return redirect()->route('perusahaan.index', ['json' => $token])->with("success", "Akun kamu sudah aktif!, Silahkan Lanjutkan ke Tahap Selanjutnya");
  }

  public function successRegister($token){
   
      $user = User::where('activation_token', $token)->first();

    if (!$user) {
        // Token tidak ditemukan
        return redirect('/login');
    }

    if (empty($user->activation_token)) {
        // Token kosong di database
        return redirect('/login');
    }

    // Kalau valid, tampilkan halaman sukses
    return view('auth.emails.registerSuccess', compact('user','token'));

  }

  public function resend(request $request){
    $token = $request->token;
    $cek = User::where('activation_token', $token)->first();

    if ($cek) {
        $newToken = Str::random(64);
        $cek->activation_token = $newToken;
        $cek->save();
        Mail::to($cek->email)->send(new AccountActivationMail($cek, $newToken));
        return response()->json(['success' => 'Token baru dikirim ke email Anda.']);
    } else {
        return response()->json(['error' => 'Token tidak ditemukan.'], 404);
    }
  }
}
