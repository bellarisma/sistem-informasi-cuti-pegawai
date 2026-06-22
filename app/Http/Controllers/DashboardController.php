<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PengajuanCuti;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. JIKA YANG LOGIN ADALAH ADMIN
        if ($user->role === 'admin') {
            // Statistik Cards
            $totalPegawai = User::where('role', 'pegawai')->count(); 
            $totalPending = PengajuanCuti::where('status', 'pending')->count();
            
            // Menghitung total divisi aktif dari pegawai yang ada
            $totalUnitKerja = User::where('role', 'pegawai')
                                  ->whereNotNull('divisi')
                                  ->distinct('divisi')
                                  ->count('divisi');

            // Mengambil 5 pengajuan CUTI terbaru (Eager Loading dengan user agar tidak null)
            $cutiTerbaru = PengajuanCuti::with('user')
                                        ->whereHas('user')
                                        ->latest()
                                        ->take(5)
                                        ->get();

            return view('dashboard.admin', compact(
                'totalPegawai', 
                'totalPending', 
                'totalUnitKerja',
                'cutiTerbaru'
            ));
        }

        // 2. JIKA YANG LOGIN ADALAH PEGAWAI BIASA
        if ($user->role === 'pegawai') {
            $riwayatCuti = PengajuanCuti::where('user_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

            return view('dashboard.pegawai', compact('user', 'riwayatCuti'));
        }

        // Antisipasi jika ada role asing
        Auth::logout();
        return redirect()->route('login');
    }
}