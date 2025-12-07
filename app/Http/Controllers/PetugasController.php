<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Denda;
use App\Models\Keluhan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengguna;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PetugasController extends Controller
{
    public function dashboard()
    {
        // Hitung ringkasan utama
        $totalBarang = Barang::count();
        $barangTersedia = Barang::where('status', 'tersedia')->count();
        $peminjamanAktif = Peminjaman::where('status', 'berlangsung')->count();
        $dendaBelumDibayar = Denda::where('status_pembayaran', 'belum')->sum('total_denda');

        $totalPengguna = Pengguna::count();
        $totalKeluhan = Keluhan::count();
        $pengembalianHariIni = Pengembalian::whereDate('waktu_pengembalian', now())->count();
        // Belum ada kolom status di tabel notifikasi, jadi gunakan total notifikasi sebagai indikator.
        $notifikasiBelumDibaca = Notifikasi::count();

        // Tentukan rentang waktu grafik
        $rangeParam  = request('range', '7hari');
        $rangePreset = [
            '7hari'  => 7,
            '1bulan' => 30,
            '3bulan' => 90,
            '1tahun' => 365,
        ];
        $isPreset   = array_key_exists($rangeParam, $rangePreset);
        $chartRange = $isPreset || $rangeParam === 'semua' ? $rangeParam : '7hari';

        $now = Carbon::now();

        // Pengaturan default (harian)
        $startDate = $now->copy()->subDays(($rangePreset[$chartRange] ?? 7) - 1)->startOfDay();
        $aggregateByMonth = false;

        // "Semua": agregasi bulanan mulai dari transaksi pertama (fallback 12 bulan ke belakang)
        if ($chartRange === 'semua') {
            $aggregateByMonth = true;
            $earliestPeminjaman   = Peminjaman::min('waktu_awal');
            $earliestPengembalian = Pengembalian::min('waktu_pengembalian');

            $earliestDate = collect([$earliestPeminjaman, $earliestPengembalian])
                ->filter()
                ->map(fn ($tanggal) => Carbon::parse($tanggal))
                ->min();

            $startDate = $earliestDate
                ? $earliestDate->copy()->startOfMonth()
                : $now->copy()->subMonths(11)->startOfMonth();
        }

        $endDate = $now->copy()->endOfDay();

        // Siapkan data grafik sesuai rentang waktu
        $chartLabels        = [];
        $chartPeminjaman    = [];
        $chartPengembalian  = [];
        $chartKetersediaan  = [];

        $groupKeyFormat = $aggregateByMonth ? 'Y-m' : 'Y-m-d';
        $labelFormat    = $aggregateByMonth ? 'M/Y' : 'd/m';
        $periodStep     = $aggregateByMonth ? '1 month' : '1 day';

        $peminjamanPerPeriode = Peminjaman::whereBetween('waktu_awal', [$startDate, $endDate])
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->waktu_awal)->format($groupKeyFormat))
            ->map->count();

        $pengembalianPerPeriode = Pengembalian::whereBetween('waktu_pengembalian', [$startDate, $endDate])
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->waktu_pengembalian)->format($groupKeyFormat))
            ->map->count();

        foreach (CarbonPeriod::create($startDate, $periodStep, $endDate) as $tanggal) {
            $key   = $tanggal->format($groupKeyFormat);
            $label = $tanggal->format($labelFormat);

            $chartLabels[]       = $label;
            $chartPeminjaman[]   = $peminjamanPerPeriode[$key] ?? 0;
            $chartPengembalian[] = $pengembalianPerPeriode[$key] ?? 0;
            $chartKetersediaan[] = $barangTersedia;
        }

        return view('petugas.dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'peminjamanAktif',
            'dendaBelumDibayar',
            'totalPengguna',
            'totalKeluhan',
            'pengembalianHariIni',
            'notifikasiBelumDibaca',
            'chartLabels',
            'chartPeminjaman',
            'chartPengembalian',
            'chartKetersediaan',
            'chartRange',
        ));
    }
}
