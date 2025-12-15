<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;


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
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        // 1. Validasi (izinkan pembaruan parsial)
        $rules = [
            'full_name' => 'sometimes|string|max:255',
            'phone' => [
                'sometimes',
                'max:15',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'sometimes',
                'email',
                'max:200',
                Rule::unique('users')->ignore($user->id),
            ],
            'address' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
            'portfolio_url' => 'sometimes|string|max:255',
            'photo' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'file_cv' => 'sometimes|mimes:pdf|max:2120',
            'education_json' => 'sometimes|json',
            'certifications_json' => 'sometimes|json',
            'languages_json' => 'sometimes|json',
            'experiences_json' => 'sometimes|json',
            'skills_json' => 'sometimes|json',
            'saved_jobs_json' => 'sometimes|json',
            'summary' => 'sometimes|string|max:2000',
            'password' => 'sometimes|string|min:6|max:255',
            'jenis_kelamin' => 'sometimes|string',
            'tanggal_lahir' => 'sometimes|date',
        ];

        $validated = $request->validate($rules);

        // Hash password bila diisi
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Gabungkan first_name + last_name ke full_name jika diisi
        $first = trim($request->input('first_name', ''));
        $last  = trim($request->input('last_name', ''));
        if ($first !== '' || $last !== '') {
            $validated['full_name'] = trim($first . ' ' . $last);
        }

        // Konversi input teks sederhana menjadi JSON untuk kolom *_json
        // Skills (CSV -> JSON array)
        if ($request->filled('skills_text')) {
            $skills = array_filter(array_map('trim', explode(',', $request->input('skills_text'))));
            $validated['skills_json'] = json_encode($skills);
        }
        // Languages (CSV -> JSON array)
        if ($request->filled('languages_text')) {
            $langs = array_filter(array_map('trim', explode(',', $request->input('languages_text'))));
            $validated['languages_json'] = json_encode($langs);
        }
        // Experience (fields -> append or edit JSON array)
        if ($request->filled('experience_title')) {
            $newExp = [
                'title'   => $request->input('experience_title'),
                'company' => $request->input('experience_company'),
                'period'  => $request->input('experience_period'),
                'desc'    => $request->input('experience_desc'),
            ];
            $existingExp = json_decode($user->experiences_json ?? '[]', true) ?: [];
            if ($request->input('item_mode') === 'edit' && $request->input('item_type') === 'experience') {
                $idx = (int) $request->input('item_index', -1);
                if ($idx >= 0 && $idx < count($existingExp)) {
                    $existingExp[$idx] = $newExp;
                } else {
                    $existingExp[] = $newExp;
                }
            } else {
                $existingExp[] = $newExp;
            }
            $validated['experiences_json'] = json_encode($existingExp);
        }
        // Education (fields -> append or edit JSON array)
        if ($request->filled('education_degree')) {
            $newEdu = [
                'degree' => $request->input('education_degree'),
                'school' => $request->input('education_school'),
                'period' => $request->input('education_period'),
                'desc'   => $request->input('education_desc'),
            ];
            $existingEdu = json_decode($user->education_json ?? '[]', true) ?: [];
            if ($request->input('item_mode') === 'edit' && $request->input('item_type') === 'education') {
                $idx = (int) $request->input('item_index', -1);
                if ($idx >= 0 && $idx < count($existingEdu)) {
                    $existingEdu[$idx] = $newEdu;
                } else {
                    $existingEdu[] = $newEdu;
                }
            } else {
                $existingEdu[] = $newEdu;
            }
            $validated['education_json'] = json_encode($existingEdu);
        }
        // Certifications (fields -> append or edit JSON array)
        if ($request->filled('cert_name')) {
            $newCert = [
                'name'   => $request->input('cert_name'),
                'issuer' => $request->input('cert_issuer'),
                'exp'    => $request->input('cert_exp'),
            ];
            $existingCert = json_decode($user->certifications_json ?? '[]', true) ?: [];
            if ($request->input('item_mode') === 'edit' && $request->input('item_type') === 'certifications') {
                $idx = (int) $request->input('item_index', -1);
                if ($idx >= 0 && $idx < count($existingCert)) {
                    $existingCert[$idx] = $newCert;
                } else {
                    $existingCert[] = $newCert;
                }
            } else {
                $existingCert[] = $newCert;
            }
            $validated['certifications_json'] = json_encode($existingCert);
        }

        // 2. Handle photo upload
        $photo = $request->file('photo');
        if ($photo) {
            $rename = uniqid().'_'. $photo->getClientOriginalName();
            $locationFile = public_path('FileUpload');
            if (!is_dir($locationFile)) {
                @mkdir($locationFile, 0777, true);
            }
            $photo->move($locationFile, $rename);
            $validated['photo'] = $rename;
        }

        // 3. Handle CV upload
        $fileCv = $request->file('file_cv');
        if ($fileCv) {
            $renameFC = uniqid().'_'. $fileCv->getClientOriginalName();
            $locationFile = public_path('FileUpload');
            if (!is_dir($locationFile)) {
                @mkdir($locationFile, 0777, true);
            }
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

        return view('User.history', [
            'user' => Auth::guard('user')->user(),
        ]);
    }

    public function applications()
    {
        return redirect('user-history');
    }

    public function savedJobs()
    {
        $user = Auth::guard('user')->user();
        $ids = collect(json_decode($user->saved_jobs_json ?? '[]', true) ?: []);
        $jobs = \App\Models\Job::with('company')->whereIn('id', $ids)->get();

        return view('User.savedJobs', [
            'user' => $user,
            'jobs' => $jobs,
        ]);
    }

    public function toggleSavedJob(\App\Models\Job $job)
    {
        $user = Auth::guard('user')->user();
        $ids = collect(json_decode($user->saved_jobs_json ?? '[]', true) ?: []);

        if ($ids->contains($job->id)) {
            $ids = $ids->reject(fn($id) => $id == $job->id)->values();
        } else {
            $ids = $ids->push($job->id)->unique()->values();
        }

        $user->update([
            'saved_jobs_json' => $ids->toJson(),
        ]);

        return back()->with('success', 'Berhasil memperbarui lowongan tersimpan.');
    }

    public function pdf(User $user) {
        $pdf = Pdf::loadView('User.cetak-profile', [
            'user' => $user->load('applyJobs.job')
        ]);

        return $pdf->download('Laporan' . $user->full_name . '.pdf');
    }
}
