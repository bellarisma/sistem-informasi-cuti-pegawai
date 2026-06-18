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
            // Mengambil data statistik untuk dashboard admin
            // Hanya menghitung user yang rolenya 'pegawai' (Admin tidak dihitung)
            $totalPegawai = User::where('role', 'pegawai')->count(); 
            
            $totalPengajuanCuti = PengajuanCuti::count();
            $totalPending = PengajuanCuti::where('status', 'pending')->count();
            
            // Mengambil 5 pegawai terbaru yang baru didaftarkan
            $pegawaiTerbaru = User::where('role', 'pegawai')
                                  ->orderBy('created_at', 'desc')
                                  ->take(5)
                                  ->get();

            return view('dashboard.admin', compact(
                'totalPegawai', 
                'totalPengajuanCuti', 
                'totalPending', 
                'pegawaiTerbaru'
            ));
        }

        // 2. JIKA YANG LOGIN ADALAH PEGAWAI biasa
        if ($user->role === 'pegawai') {
            // Mengambil riwayat pengajuan cuti milik pegawai ini sendiri (maksimal 5 data terbaru)
            $riwayatCuti = PengajuanCuti::where('user_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

            return view('dashboard.pegawai', compact('user', 'riwayatCuti'));
        }

        // Antisipasi jika ada role asing, lempar ke halaman login
        Auth::logout();
        return redirect()->route('login');
    }
}