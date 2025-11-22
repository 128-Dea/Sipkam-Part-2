<?php

namespace App\Http\Controllers;

use App\Models\Qr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function index()
    {
        $qr = Qr::with(['peminjaman'])->orderByDesc('id_qr')->get();

        return view('qr.index', compact('qr'));
    }

    public function show(Qr $qr)
    {
        $qr->load(['peminjaman']);
        $qrImage = QrCode::format('svg')->size(200)->generate($qr->payload);

        return view('qr.show', compact('qr', 'qrImage'));
    }
}
