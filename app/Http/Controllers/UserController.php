<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('User.profile', [
            'user' => Auth::guard('user')->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        // 1. Validasi
        $rules = [
            'full_name' => 'required|string|max:255',
            'phone' => [
                'required',
                'max:15',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:200',
                Rule::unique('users')->ignore($user->id),
            ],
            'address' => 'required|string',
            'photo' => 'image|mimes:jpg,jpeg,png|max:2048',
            'file_cv' => 'mimes:pdf|max:2120',
            'education_json' => 'required|json',
            'certifications_json' => 'json',
            'languages_json' => 'json',
            'experiences_json' => 'json',
            'skills_json' => 'json',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|max:20|min:6';
        }

        $validated = $request->validate($rules);

        // 2. Handle photo upload
        $photo = $request->file('photo');
        if ($photo) {

            $rename = uniqid().'_'.$photo->getClientOriginalName();

            $locationFile = 'FileUpload';

            $photo->move($locationFile, $rename);

            $validated['photo'] = $rename;

        }

        // 3. Handle CV upload
        $fileCv = $request->file('file_cv');

        if ($fileCv) {

            $renameFC = uniqid().'_'.$fileCv->getClientOriginalName();

            $locationFile = 'FileUpload';

            $fileCv->move($locationFile, $renameFC);

            $validated['file_cv'] = $renameFC;

        }

        // 4. Simpan ke database
        $user->update($validated);

        // 5. Redirect sukses
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function history()
    {

        // return Auth::guard('user')->user()->load('applyJobs');

        return view('User.history', [
            'user' => Auth::guard('user')->user(),
        ]);
    }
}
