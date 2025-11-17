<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function index()
    {
        $qr = Qr::with(['peminjaman', 'serahTerima'])->orderByDesc('id_qr')->get();

        return view('qr.index', compact('qr'));
    }

    public function show(Qr $qr)
    {
        $qr->load(['peminjaman', 'serahTerima']);
        $qrImage = QrCode::format('svg')->size(200)->generate($qr->qr_code);

        return view('qr.show', compact('qr', 'qrImage'));
    }
}
