<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    public function show()
    {
        return view('public.verify-certificate');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'certificate_number' => 'required|string',
        ]);

        $certificate = Certificate::with(['student', 'course'])
            ->where('certificate_number', $validated['certificate_number'])
            ->first();

        if (! $certificate) {
            return redirect()->back()->withErrors(['certificate_number' => 'Certificate not found.']);
        }

        return view('public.verify-certificate', compact('certificate'));
    }

    public function scan(string $number)
    {
        $certificate = Certificate::with(['student', 'course'])
            ->where('certificate_number', $number)
            ->first();

        return view('public.verify-certificate', [
            'certificate' => $certificate,
            'scanned_number' => $number,
        ]);
    }
}
