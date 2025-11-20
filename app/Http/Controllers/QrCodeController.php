<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Menampilkan halaman scanner QR code
     */
    public function scanner()
    {
        return view('qr-scanner');
    }
}