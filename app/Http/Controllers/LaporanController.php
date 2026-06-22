<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\User;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua list divisi unik dari database untuk pilihan di dropdown filter
        $listDivisi = User::where('role', 'pegawai')
                            ->whereNotNull('divisi')
                            ->distinct()
                            ->pluck('divisi');

        // 2. Mulai query dasar ambil data cuti
        $query = PengajuanCuti::with('user')->whereHas('user');

        // 3. Filter berdasarkan Periode Tanggal (jika diisi)
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('tanggal_mulai', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // 4. Filter berdasarkan Divisi Karyawan (jika dipilih)
        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        // 5. Eksekusi data terbaru
        $reports = $query->latest()->get();

        // 6. Lempar data ke blade
        return view('cuti.laporan', compact('reports', 'listDivisi'));
    }
}