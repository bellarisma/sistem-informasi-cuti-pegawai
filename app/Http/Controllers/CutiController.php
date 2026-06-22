<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotifikasiCutiMail;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CutiController extends Controller
{
    /**
     * Menampilkan halaman cuti berdasarkan Role
     */
    public function index()
    {
        $user = Auth::user();

        // 1. JIKA LOGIN SEBAGAI ADMIN (Melihat semua pengajuan masuk)
        if ($user->role === 'admin') {
            // Mengambil semua data pengajuan cuti beserta data pegawainya (Eager Loading)
            $pengajuanCuti = PengajuanCuti::with('user')
                                        ->orderBy('created_at', 'desc')
                                        ->get();

            return view('cuti.admin_index', compact('pengajuanCuti'));
        }

        // 2. JIKA LOGIN SEBAGAI PEGAWAI (Melihat data sendiri & Form Pengajuan)
        if ($user->role === 'pegawai') {
            $riwayatCuti = PengajuanCuti::where('user_id', $user->id)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

            return view('cuti.pegawai_index', compact('user', 'riwayatCuti'));
        }
    }

    /**
     * Memproses Form Pengajuan Cuti dari Pegawai
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input Form
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'          => 'required|string|min:5',
        ], [
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh hari yang sudah lewat.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'alasan.required' => 'Alasan cuti wajib diisi.'
        ]);

        // 2. Hitung jumlah hari cuti yang diajukan
        $mulai = Carbon::parse($request->tanggal_mulai);
        $selesai = Carbon::parse($request->tanggal_selesai);
        $jumlahHari = $mulai->diffInDays($selesai) + 1; // +1 agar hari mulai ikut terhitung

        // 3. Validasi sisa jatah cuti pegawai
        if ($user->sisa_jatah_cuti < $jumlahHari) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Pengajuan gagal! Jatah cuti Anda tinggal {$user->sisa_jatah_cuti} hari, sedangkan Anda mengajukan cuti selama {$jumlahHari} hari.");
        }

        // 4. Cek double-click / Double Input (Cek apakah sudah ada pengajuan pending di tanggal yang sama)
        $cekDouble = PengajuanCuti::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function($query) use ($request) {
                $query->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                      ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai]);
            })->exists();

        if ($cekDouble) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda sudah memiliki pengajuan cuti yang berstatus PENDING di tanggal tersebut!');
        }

        // 5. Simpan Data ke Database
        $cuti = PengajuanCuti::create([
            'user_id'         => $user->id,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan'          => $request->alasan,
            'status'          => 'pending', 
            'jml_hari_cuti'   => $jumlahHari,
        ]);

       // Kirim email notifikasi ke Admin menggunakan variabel $cuti
        $admin = User::where('role', 'admin')->first();
        if ($admin && $admin->email) {
            $pesan = "Halo Admin, ada pengajuan cuti baru yang masuk dan memerlukan persetujuan Anda.";
            Mail::to($admin->email)->send(new NotifikasiCutiMail($cuti, $pesan));
        }

        return redirect()->route('cuti.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim! Silakan tunggu konfirmasi dari Admin.');
    }

    /**
     * Fitur Approval Admin: SETUJUI CUTI
     */
    public function setujui($id)
    {
        $cuti = PengajuanCuti::findOrFail($id);
        
        if ($cuti->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya!');
        }

        // Hitung hari cuti
        $mulai = Carbon::parse($cuti->tanggal_mulai);
        $selesai = Carbon::parse($cuti->tanggal_selesai);
        $jumlahHari = $mulai->diffInDays($selesai) + 1;

        $pegawai = User::findOrFail($cuti->user_id);

        // Kurangi sisa jatah cuti pegawai
        if ($pegawai->sisa_jatah_cuti >= $jumlahHari) {
            $pegawai->decrement('sisa_jatah_cuti', $jumlahHari);
            $cuti->update(['status' => 'disetujui']);
        
           // Kirim email (Ganti $pengajuanCuti menjadi $cuti)
            if ($cuti->user && $cuti->user->email) {
                $pesan = "Selamat! Pengajuan cuti Anda telah disetujui oleh Admin.";
                Mail::to($cuti->user->email)->send(new NotifikasiCutiMail($cuti, $pesan));
            }

            return redirect()->back()->with('success', 'Pengajuan cuti berhasil DISETUJUI dan jatah cuti pegawai telah dipotong.');
        }

        return redirect()->back()->with('error', 'Gagal menyetujui! Sisa jatah cuti pegawai tidak mencukupi.'); 
        }

    /**
     * Fitur Approval Admin: TOLAK CUTI
     */
    public function tolak($id)
    {
        $cuti = PengajuanCuti::findOrFail($id);
        
        if ($cuti->status !== 'pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya!');
        }

        // Kirim email (Ganti $pengajuanCuti menjadi $cuti)
        if ($cuti->user && $cuti->user->email) {
            $pesan = "Mohon maaf, pengajuan cuti Anda ditolak oleh Admin karena alasan operasional.";
            Mail::to($cuti->user->email)->send(new NotifikasiCutiMail($cuti, $pesan));
        }

        return redirect()->back()->with('success', 'Pengajuan cuti telah DITOLAK.');
    }
}