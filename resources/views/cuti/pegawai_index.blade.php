@extends('layouts.app')

@section('content')
<div class="topbar" style="margin-bottom: 25px;">
    <h2>Pengajuan Cuti</h2>
    <p>Ajukan permohonan cuti baru dan pantau status riwayat cuti Anda di sini.</p>
</div>

{{-- Tempat Pesan Error --}}
@if(session('error'))
    <div id="error-alert" style="background: #ef4444; color: white; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; transition: opacity 0.5s ease;">
        <i class="fa-solid fa-circle-xmark" style="font-size: 18px;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if($errors->any())
    <div id="validation-alert" style="background: #ef4444; color: white; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-weight: 500; transition: opacity 0.5s ease;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
            <i class="fa-solid fa-circle-exclamation" style="font-size: 18px;"></i>
            <strong>Pengajuan Gagal:</strong>
        </div>
        <ul style="margin: 0; padding-left: 28px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div id="success-alert" style="background: #10b981; color: white; padding: 15px; border-radius: 10px; margin-bottom: 25px; font-weight: 500; display: flex; align-items: center; gap: 10px; transition: opacity 0.5s ease;">
        <i class="fa-solid fa-circle-check" style="font-size: 18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- 1. FORMULIR CUTI BARU --}}
<div class="table-box" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
        <h3 style="font-size: 18px; color: #1e293b; display: flex; align-items: center; gap: 8px; margin: 0;">
            <i class="fa-solid fa-paper-plane" style="color: #3b82f6;"></i> Formulir Cuti Baru
        </h3>
        <span style="font-size: 14px; background: #f0fdf4; color: #166534; padding: 6px 14px; border-radius: 99px; font-weight: 600; border: 1px solid #bbf7d0;">
            Sisa Jatah Cuti Anda: {{ auth()->user()->sisa_jatah_cuti }} Hari
        </span>
    </div>

    <form action="{{ route('cuti.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #475569; font-size: 14px;">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                    style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; color: #334155; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #475569; font-size: 14px;">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                    style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; color: #334155; outline: none; transition: border 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #475569; font-size: 14px;">Alasan Cuti</label>
            <textarea name="alasan" rows="3" placeholder
                style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: inherit; color: #334155; outline: none; resize: none; transition: border 0.2s;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#cbd5e1'">{{ old('alasan') }}</textarea>
        </div>

        <div style="text-align: right;">
            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;"
                onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan
            </button>
        </div>
    </form>
</div>

{{-- 2. TABEL RIWAYAT PENGADAAN CUTI --}}
<div class="table-box" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <h3 style="font-size: 18px; color: #1e293b; margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
        <i class="fa-solid fa-clock-rotate-left" style="color: #64748b;"></i> Riwayat Pengajuan Cuti Anda
    </h3>
    
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px 16px; color: #475569; font-weight: 600;">No</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Tanggal Mulai</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Tanggal Selesai</th>
                <th style="padding: 12px; color: #475569; font-weight: 600;">Alasan</th>
                <th style="padding: 12px; color: #475569; font-weight: 600; text-align: center;">Durasi</th>
                <th style="padding: 12px 16px; color: #475569; font-weight: 600; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatCuti as $index => $cuti)
            <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 16px; color: #64748b;">{{ $index + 1 }}</td>
                <td style="padding: 14px; color: #334155; font-weight: 500;">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->translatedFormat('d M Y') }}</td>
                <td style="padding: 14px; color: #334155; font-weight: 500;">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->translatedFormat('d M Y') }}</td>
                <td style="padding: 14px; color: #64748b; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cuti->alasan }}">
                    {{ $cuti->alasan }}
                </td>
                <td style="padding: 14px; text-align: center; font-weight: 600; color: #0f172a;">{{ $cuti->jml_hari_cuti }} Hari</td>
                <td style="padding: 14px 16px; text-align: center;">
                    @if(strtolower($cuti->status) === 'pending')
                        <span style="background: #fef9c3; color: #713f12; padding: 5px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; display: inline-block; border: 1px solid #fef08a;">
                            <i class="fa-solid fa-spinner fa-spin me-1"></i> Pending
                        </span>
                    @elseif(strtolower($cuti->status) === 'disetujui')
                        <span style="background: #dcfce7; color: #15803d; padding: 5px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; display: inline-block; border: 1px solid #bbf7d0;">
                            <i class="fa-solid fa-circle-check me-1"></i> Disetujui
                        </span>
                    @else
                        <span style="background: #fee2e2; color: #b91c1c; padding: 5px 14px; border-radius: 99px; font-size: 13px; font-weight: 600; display: inline-block; border: 1px solid #fecaca;">
                            <i class="fa-solid fa-circle-xmark me-1"></i> Ditolak
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 40px; text-align: center; color: #94a3b8;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                    Belum ada riwayat pengajuan cuti.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    // Fade out otomatis untuk pesan alert biar mulus
    const alerts = ['#success-alert', '#error-alert', '#validation-alert'];
    alerts.forEach(selector => {
        const element = document.querySelector(selector);
        if (element) {
            setTimeout(() => {
                element.style.opacity = '0';
                setTimeout(() => { element.style.display = 'none'; }, 500);
            }, 4000);
        }
    });
</script>
@endsection