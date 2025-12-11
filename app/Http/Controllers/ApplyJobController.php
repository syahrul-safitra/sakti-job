<?php

namespace App\Http\Controllers;

use App\Models\ApplyJob;
use App\Models\Job;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;

class ApplyJobController extends Controller
{
    public function store(Request $request, Job $job)
    {
        // Validasi input jika diperlukan
        $validate = $request->validate([
            'cover_letter' => 'max:1000',
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
        ]);

        $checkExistingApply = ApplyJob::where('user_id', $validate['user_id'])
            ->where('job_id', $validate['job_id'])
            ->get();

        if ($checkExistingApply->count() > 0) {
            return redirect()->back()->with('error', 'Anda sudah melamar pekerjaan ini sebelumnya.');
        }

        ApplyJob::create($validate);

        return redirect('user-history')->with('success', 'Berhasil melamar pekerjaan!');
    }

    public function indexCompany()
    {

        return view('Company.Pelamar.index', [
            'jobs' => Job::with('applyJobs.user')->where('company_id', Auth::guard('company')->user()->id)->get(),
        ]);
    }

    public function updateStatusPelamar(Request $request, ApplyJob $apply)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:accepted,rejected,pending',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $apply->status = $validatedData['status'];
        $apply->keterangan = $validatedData['keterangan'];

        $apply->save();

        return redirect('company-applyjob')->with('swal', [
            'icon' => 'success',
            'title' => 'Status Pelamar Diperbarui',
            'text' => 'Status pelamar telah berhasil diperbarui.',
        ]);
    }

    public function show(ApplyJob $apply)
    {
        return view('Company.Pelamar.detail', [
            'apply' => $apply->load('user', 'job.company'),
        ]);
    }
}
