<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }

    public function registerCompany()
    {
        return view('Auth.register-company');
    }

    public function doRegisterCompany(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|string|email|max:50|unique:users|unique:companies|unique:admins,email',
            'password' => 'required|string|confirmed',
            'name' => 'required|string|max:100|unique:companies',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20|unique:users|unique:companies',
            'link_website' => 'max:255',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        Company::create($validatedData);

        return redirect('login')->with('success', 'Berhasil mendaftarkan company, silahkan login');

    }

    public function doLogin(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|max:100|email:dns',
            'password' => 'required|max:20',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {

            return redirect('/dashboard')->with('swal', [
                'icon' => 'info',
                'title' => 'Selamat Datang Admin',
                'text' => 'Silahkan melakukan verifikasi perusahaan.',
            ]);
        }

        if (Auth::guard('company')->attempt($credentials)) {

            $getData = Auth::guard('company')->user();

            if ($getData->status === 'verified') {
                return redirect('/dashboard-company')->with('swal', [
                    'icon' => 'info',
                    'title' => 'Perusahaan anda telah diverifikasi',
                    'text' => 'Promosikan lowongan perusahaan sekarang',
                ]);
            }

            if (! $getData->description && $getData->status == 'pending') {
                return redirect('dashboard-company')->with('swal', [
                    'icon' => 'info',
                    'title' => 'Lengkapi Profil Perusahaan',
                    'text' => 'Lengkapi data perusahaan untuk proses verifikasi admin sebelum memasang lowongan.',
                ]);

            }

            return redirect('dashboard-company')->with('swal', [
                'icon' => 'info',
                'title' => 'Menunggu Verifikasi',
                'text' => 'Profil perusahaan anda sedang di tinjau oleh admin.',
            ]);
        }

        if (Auth::guard('user')->attempt($credentials)) {
            return redirect('/')->with('swal', [
                'icon' => 'info',
                'title' => 'Selamat Datang',
                'text' => 'Jelajahi lowongan pekerjaan yang tersedia.',
            ]);
        }

        return back()->with('loginFailed', 'Login Failed');

    }

    public function registerUser()
    {
        return view('Auth.register-user');
    }

    public function doRegisterUser(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:200',
            'phone' => 'required|string|max:20|unique:users|unique:companies',
            'email' => 'required|string|email|max:50|unique:users|unique:companies|unique:admins,email',
            'password' => 'required|string|confirmed',
            'link_website' => 'max:255',
        ]);

        User::create($validatedData);

        return redirect('login')->with('success', 'Berhasil mendaftarkan user, silahkan login');

    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
        } else {
            Auth::guard('user')->logout();
        }

        return redirect('/');
    }
}
