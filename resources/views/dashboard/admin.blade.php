@extends('layouts.app')

@section('content')
<div class="topbar" style="margin-bottom: 25px;">
    <h2>Dashboard Admin</h2>
    <p>Selamat Datang kembali, Admin! Pantau produktivitas dan operasional pegawai di sini.</p>
</div>

{{-- 1. ROW STATISTIK (CARDS) DENGAN WARNA PASTEL TEKNOLOGI --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    
    <!-- Card 1: Total Karyawan -->
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
        <div style="background: #3b82f6; color: white; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <span style="display: block; font-size: 14px; color: #64748b; font-weight: 500;">Total Karyawan</span>
            <strong style="font-size: 24px; color: #1e293b;">{{ $totalPegawai }} Karyawan</strong>
        </div>
    </div>

    <!-- Card 2: Perlu Persetujuan -->
    <div style="background: #fefce8; border: 1px solid #fef08a; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
        <div style="background: #eab308; color: white; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div>
            <span style="display: block; font-size: 14px; color: #64748b; font-weight: 500;">Perlu Persetujuan</span>
            <strong style="font-size: 24px; color: #1e293b;">{{ $totalPending }} Pengajuan</strong>
        </div>
    </div>

    <!-- Card 3: Total Unit Kerja -->
    <div style="background: #faf5ff; border: 1px solid #e9d5ff; padding: 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
        <div style="background: #a855f7; color: white; width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
            <i class="fa-solid fa-building"></i>
        </div>
        <div>
            <span style="display: block; font-size: 14px; color: #64748b; font-weight: 500;">Total Unit Kerja</span>
            <strong style="font-size: 24px; color: #1e293b;">{{ $totalUnitKerja }} Divisi Aktif</strong>
        </div>
    </div>

</div>

{{-- 2. RINGKASAN AKTIVITAS CUTI TERBARU --}}
<div class="table-box" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
        <h3 style="font-size: 18px; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-bell" style="color: #64748b;"></i> Aktivitas Pengajuan Terbaru
        </h3>
        <a href="{{ route('cuti.index') }}" style="font-size: 14px; color: #3b82f6; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px;">
            Lihat Semua Manajemen Cuti <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px; color: #475569; font-weight: 600;">Nama Karyawan</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Tanggal Cuti</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Alasan</th>
                <th style="padding: 12px; color: #475569; font-weight: 600; text-align: center;">Durasi</th>
                <th style="padding: 12px; color: #475569; font-weight: 600; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cutiTerbaru as $cuti)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 14px 12px; color: #1e293b; font-weight: 500;">{{ $cuti->user->name }}</td>
                <td style="padding: 14px 12px; color: #475569; font-size: 14px;">
                    {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->translatedFormat('d M Y') }}
                </td>
                <td style="padding: 14px 12px; color: #64748b; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cuti->alasan }}">
                    {{ $cuti->alasan }}
                </td>
                <td style="padding: 14px 12px; text-align: center; font-weight: 600; color: #1e293b;">{{ $cuti->jml_hari_cuti }} Hari</td>
                <td style="padding: 14px 12px; text-align: center;">
                    @if(strtolower($cuti->status) === 'pending')
                        <span style="background: #fef9c3; color: #713f12; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #fef08a;">
                            Pending
                        </span>
                    @elseif(strtolower($cuti->status) === 'disetujui')
                        <span style="background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #bbf7d0;">
                            Disetujui
                        </span>
                    @else
                        <span style="background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #fecaca;">
                            Ditolak
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 30px; text-align: center; color: #94a3b8;">
                    Belum ada riwayat aktivitas pengajuan masuk.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection