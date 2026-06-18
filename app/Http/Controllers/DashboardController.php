<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PengajuanCuti;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalCuti = PengajuanCuti::count();
        $totalUnitKerja = User::where('role', 'pegawai')->whereNotNull('divisi')->distinct('divisi')->count('divisi');
        $pegawaiTerbaru = User::where('role', 'pegawai')->latest()->take(5)->get();

        return view('dashboard', compact('totalPegawai', 'totalCuti', 'totalUnitKerja', 'pegawaiTerbaru'));
    }
}