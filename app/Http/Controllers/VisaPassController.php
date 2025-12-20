<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class VisaPassController extends Controller
{
    public function index()
    {
        return view('visa-pass.index');
    }

    public function downloadPdf(Request $request, $filename)
    {
        // Define the allowed PDF files
        $allowedFiles = [
            'borang-permohonan-visa' => 'Borang Permohonan Visa.pdf',
            'permohonan-pas-tanggungan' => 'Permohonan Bagi Satu Pas Tanggungan (Borang 25).pdf',
            'permohonan-kebenaran-tinggal' => 'Permohonan Kebenaran Tinggal Sementara Dalam Negara Brunei Darussalam (Borang 8).pdf'
        ];

        // Check if the requested file is allowed
        if (!array_key_exists($filename, $allowedFiles)) {
            abort(404, 'File not found');
        }

        $filePath = public_path('images/' . $allowedFiles[$filename]);

        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }

        // Return the file as download
        return response()->download($filePath, $allowedFiles[$filename]);
    }
}
