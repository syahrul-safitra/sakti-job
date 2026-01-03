<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function login() {
        return view('Auth.login');
    }

    public function registerCompany() {
        return view('Auth.register-company');
    }

    public function doRegisterCompany(Request $request) {

        $validatedData = $request->validate([
            'email'          => 'required|string|email|max:50|unique:users|unique:companies|unique:admins,email',
            'password'       => 'required|string|confirmed', 
            'name'           => 'required|string|max:100|unique:companies',
            'address'        => 'required|string|max:500',
            'phone'          => 'required|string|max:20|unique:users|unique:companies',
            'link_website'   => 'max:255',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        Company::create($validatedData);

        return redirect('login')->with('success', 'Berhasil mendaftarkan company, silahkan login');

    }

    public function doLogin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|max:100|email',
            'password' => 'required|max:255'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {

            return redirect('/dashboard')->with('swal', [
                        'icon'  => 'info',
                        'title' => 'Selamat Datang Admin',
                        'text'  => 'Silahkan melakukan verifikasi perusahaan.'
                ]);
        }

        if (Auth::guard('user')->attempt($credentials)) {
            return redirect('/')->with('swal', [
                'icon'  => 'success',
                'title' => 'Berhasil Login',
                'text'  => 'Selamat datang di SaktiJob.'
            ]);
        }

        if (Auth::guard('company')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            // 'status' => 'verified'
        ])) {

            $getData = Auth::guard('company')->user();

            if (!$getData->description) {
                return redirect('/lengkapi-profile')->with('swal', [
                    'icon'  => 'info',
                    'title' => 'Lengkapi Profil Perusahaan',
                    'text'  => 'Lengkapi profil perusahaan Anda sebelum memasang lowongan.'
                ]);
            }

            return redirect('/dashboard-company')->with('swal', [
                'icon'  => 'success',
                'title' => 'Berhasil Login',
                'text'  => 'Akun perusahaan terverifikasi. Promosikan lowongan sekarang.'
            ]);
        }

        $adminAcc = Admin::where('email', $credentials['email'])->first();
        
        if ($adminAcc && !Hash::check($credentials['password'], $adminAcc->password)) {
            return back()->with('loginFailed', 'Password admin salah');
        }

        $userAcc = User::where('email', $credentials['email'])->first();
        if ($userAcc) {
            if (!Hash::check($credentials['password'], $userAcc->password)) {
                return back()->with('loginFailed', 'Password user salah');
            }

            if (Auth::guard('user')->attempt($credentials)) {
                return redirect('/')->with('swal', [
                    'icon'  => 'success',
                    'title' => 'Berhasil Login',
                    'text'  => 'Selamat datang di SaktiJob.'
                ]);
            }
        }

        $company = Company::where('email', $credentials['email'])->first();
        if ($company) {
            if (!Hash::check($credentials['password'], $company->password)) {
                return back()->with('loginFailed', 'Password perusahaan salah');
            }

            if ($company->status === 'verified') {
                Auth::guard('company')->login($company);

                if (!$company->description) {
                    return redirect('/lengkapi-profile')->with('swal', [
                        'icon'  => 'info',
                        'title' => 'Lengkapi Profil Perusahaan',
                        'text'  => 'Lengkapi profil perusahaan Anda sebelum memasang lowongan.'
                    ]);
                }

                return redirect('/dashboard-company')->with('swal', [
                    'icon'  => 'success',
                    'title' => 'Berhasil Login',
                    'text'  => 'Akun perusahaan terverifikasi. Promosikan lowongan sekarang.'
                ]);
            }

            if ($company->status === 'pending') {
                return back()->with('swal', [
                    'icon'  => 'info',
                    'title' => 'Menunggu Verifikasi',
                    'text'  => 'Perusahaan Anda dalam proses verifikasi oleh admin.'
                ]);
            }

            if ($company->status === 'rejected') {
                return back()->with('swal', [
                    'icon'  => 'warning',   
                    'title' => 'Akun Ditolak',
                    'text'  => 'Perusahaan Anda belum memenuhi persyaratan untuk mendaftarkan lowongan di sini.'
                ]);
            }
        }

        return back()->with('loginFailed', 'Email tidak terdaftar');

        return back()->with('loginFailed', 'Login gagal');
        
    }

    public function registerUser() {
        return view('Auth.register-user');
    }

    public function doRegisterUser(Request $request) {
        $validatedData = $request->validate([
            'full_name'      => 'required|string|max:200',
            'phone'          => 'required|string|max:20|unique:users|unique:companies',
            'email'          => 'required|string|email|max:50|unique:users|unique:companies|unique:admins,email',
            'password'       => 'required|string|confirmed', 
            'link_website'   => 'max:255',
        ]);

        User::create($validatedData);

        return redirect('login')->with('success', 'Berhasil mendaftarkan user, silahkan login');

    }

    public function logout(Request $request) {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } elseif (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
