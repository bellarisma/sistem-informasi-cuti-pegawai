<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
    /**
     * Display a listing of leave applications.
     */
    public function index()
    {
        // Jika admin, ambil semua pengajuan cuti.
        // Jika pegawai, ambil pengajuan cuti miliknya saja.
        if (auth()->user()->role === 'admin') {
            $pengajuanCuti = PengajuanCuti::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $pengajuanCuti = PengajuanCuti::with('user')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('cuti.index', compact('pengajuanCuti'));
    }

    /**
     * Approve a leave application.
     */
    public function setujui($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $cuti = PengajuanCuti::findOrFail($id);

        if ($cuti->status !== 'Pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        $user = $cuti->user;

        if ($user->sisa_jatah_cuti < $cuti->jml_hari_cuti) {
            return redirect()->back()->with('error', "Sisa jatah cuti pegawai ({$user->name}) tidak mencukupi. Sisa jatah: {$user->sisa_jatah_cuti} hari, pengajuan: {$cuti->jml_hari_cuti} hari.");
        }

        DB::transaction(function () use ($cuti, $user) {
            // Kurangi sisa jatah cuti pegawai
            $user->sisa_jatah_cuti -= $cuti->jml_hari_cuti;
            $user->save();

            // Ubah status pengajuan
            $cuti->status = 'Disetujui';
            $cuti->save();
        });

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil disetujui!');
    }

    /**
     * Reject a leave application.
     */
    public function tolak($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $cuti = PengajuanCuti::findOrFail($id);

        if ($cuti->status !== 'Pending') {
            return redirect()->back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        $cuti->status = 'Ditolak';
        $cuti->save();

        return redirect()->back()->with('success', 'Pengajuan cuti telah ditolak.');
    }
}