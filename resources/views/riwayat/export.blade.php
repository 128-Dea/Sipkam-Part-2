<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>riwayat Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h2>riwayat Transaksi SIPKAM</h2>
    <p>Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
    <table>
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Barang</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Pengembalian</th>
                <th>Kondisi</th>
                <th>Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalian as $item)
                @php
                    $p = $item->peminjaman;
                    $kondisi = $p?->barang?->status ?? '-';
                    $denda = $p?->denda?->sum('total_denda') ?? 0;
                @endphp
                <tr>
                    <td>{{ $p?->pengguna?->nama ?? '-' }}</td>
                    <td>{{ $p?->barang?->nama_barang ?? '-' }}</td>
                    <td>{{ $p?->waktu_awal }}</td>
                    <td>{{ $item->waktu_pengembalian }}</td>
                    <td>{{ $kondisi }}</td>
                    <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
