@extends('layouts.app')

@section('content')
<div class="topbar" style="margin-bottom: 25px;">
    <h2>Laporan Rekap Cuti Pegawai</h2>
    <p>Filter, analisis, dan cetak riwayat pengajuan cuti seluruh pegawai perusahaan.</p>
</div>

{{-- 1. BOX FILTER PERIODE & DIVISI --}}
<div class="table-box" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 25px;">
    <h3 style="font-size: 16px; color: #1e293b; margin-top: 0; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-filter" style="color: #3b82f6;"></i> Filter Laporan
    </h3>
    
    <form action="{{ route('laporan.index') }}" method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div>
            <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #475569; font-size: 13px;">Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" 
                style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; outline: none;">
        </div>
        <div>
            <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #475569; font-size: 13px;">Tanggal Selesai</label>
            <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" 
                style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; outline: none;">
        </div>
        
        {{-- Dropdown Filter Divisi Baru --}}
        <div>
            <label style="display: block; margin-bottom: 6px; font-weight: 500; color: #475569; font-size: 13px;">Pilih Divisi</label>
            <select name="divisi" style="padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; color: #334155; outline: none; background: white; min-width: 150px;">
                <option value="">-- Pilih Divisi --</option>
                @foreach($listDivisi as $div)
                    <option value="{{ $div }}" {{ request('divisi') == $div ? 'selected' : '' }}>{{ $div }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 9px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>
            
            @if(request('tgl_mulai') || request('tgl_selesai') || request('divisi'))
                <a href="{{ route('laporan.index') }}" style="background: #64748b; color: white; text-decoration: none; padding: 9px 16px; border-radius: 6px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            @endif

            <button type="button" onclick="window.print()" style="background: #10b981; color: white; border: none; padding: 9px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-print"></i> Cetak PDF / Print
            </button>
        </div>
    </form>
</div>

{{-- 2. TABEL DATA REKAP LAPORAN --}}
<div class="table-box print-area" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
        <h3 style="font-size: 18px; color: #1e293b; margin: 0;">Data Pengajuan Cuti Seluruh Karyawan</h3>
        @if(request('tgl_mulai') && request('tgl_selesai'))
            <p style="margin: 5px 0 0 0; font-size: 13px; color: #64748b;">
                Periode: <strong>{{ \Carbon\Carbon::parse(request('tgl_mulai'))->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse(request('tgl_selesai'))->translatedFormat('d M Y') }}</strong>
            </p>
        @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px; color: #475569; font-weight: 600;">Nama Karyawan</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Divisi</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Tanggal Mulai</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Tanggal Selesai</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Alasan</th>
                <th style="padding: 12px; color: #475569; font-weight: 600; text-align: center;">Durasi</th>
                <th style="padding: 12px; color: #475569; font-weight: 600; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>

    @forelse($reports as $cuti)
    <tr style="border-bottom: 1px solid #e2e8f0;">
        <td style="padding: 14px 12px; color: #1e293b; font-weight: 500;">{{ $cuti->user->name }}</td>
        <td style="padding: 14px 12px; color: #475569;">
            <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 13px; font-weight: 500;">
                {{ $cuti->user->divisi ?? '-' }}
            </span>
        </td>
        <td style="padding: 14px 12px; color: #475569;">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->translatedFormat('d M Y') }}</td>
        <td style="padding: 14px 12px; color: #475569;">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->translatedFormat('d M Y') }}</td>
        <td style="padding: 14px 12px; color: #64748b; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cuti->alasan }}">
            {{ $cuti->alasan }}
        </td>
        <td style="padding: 14px 12px; text-align: center; font-weight: 600; color: #1e293b;">{{ $cuti->jml_hari_cuti }} Hari</td>
        <td style="padding: 14px 12px; text-align: center;">
            @if(strtolower($cuti->status) === 'pending')
                <span style="background: #fef9c3; color: #713f12; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #fef08a;">Pending</span>
            @elseif(strtolower($cuti->status) === 'disetujui')
                <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #bbf7d0;">Disetujui</span>
            @else
                <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; border: 1px solid #fecaca;">Ditolak</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 30px; text-align: center; color: #94a3b8;">
            Tidak ada data pengajuan cuti yang sesuai dengan periode filter.
        </td>
    </tr>
    @endforelse
</tbody>
    </table>
</div>

{{-- CSS KHUSUS PRINT: Biar pas di-print, topbar dan form filternya ilang otomatis --}}
<style>
@media print {
    body { background: white; color: black; padding: 0; margin: 0; }
    .sidebar, .topbar, form, button, a { display: none !important; }
    .print-area { box-shadow: none !important; padding: 0 !important; width: 100% !important; }
    table { width: 100% !important; border: 1px solid #cbd5e1; }
    th, td { border-bottom: 1px solid #cbd5e1 !important; padding: 10px !important; }
}
</style>
@endsection