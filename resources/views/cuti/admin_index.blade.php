@extends('layouts.app')

@section('content')
<div class="topbar">
    <h2>Manajemen Cuti</h2>
    <p>Lihat dan proses pengajuan cuti pegawai perusahaan di sini.</p>
</div>

@if(session('success'))
    <div id="success-alert" style="background: #10b981; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; transition: opacity 0.5s ease;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="error-alert" style="background: #ef4444; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; transition: opacity 0.5s ease;">
        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
    </div>
@endif

<div class="table-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Daftar Pengajuan Cuti</h3>
        @if(auth()->user()->role === 'pegawai')
            <span style="font-size: 14px; background: #e2e8f0; color: #475569; padding: 6px 12px; border-radius: 8px; font-weight: 500;">
                Sisa Jatah Cuti Anda: <strong>{{ auth()->user()->sisa_jatah_cuti }} Hari</strong>
            </span>
        @endif
    </div>
    
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px;">Nama Pegawai</th>
                <th style="padding: 12px;">Tanggal Mulai</th>
                <th style="padding: 12px;">Tanggal Selesai</th>
                <th style="padding: 12px;">Alasan</th>
                <th style="padding: 12px; text-align: center;">Jml Hari</th>
                @if(auth()->user()->role === 'admin')
                    <th style="padding: 12px; text-align: center;">Sisa Jatah</th>
                @endif
                <th style="padding: 12px; text-align: center;">Status</th>
                @if(auth()->user()->role === 'admin')
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuanCuti as $cuti)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px; font-weight: 500; color: #1e293b;">{{ $cuti->user->name }}</td>
                <td style="padding: 12px; color: #475569;">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->translatedFormat('d M Y') }}</td>
                <td style="padding: 12px; color: #475569;">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->translatedFormat('d M Y') }}</td>
                <td style="padding: 12px; color: #64748b; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cuti->alasan }}">
                    {{ $cuti->alasan }}
                </td>
                <td style="padding: 12px; text-align: center; font-weight: 600; color: #1e293b;">{{ $cuti->jml_hari_cuti }} Hari</td>
                @if(auth()->user()->role === 'admin')
                    <td style="padding: 12px; text-align: center; color: #475569;">{{ $cuti->user->sisa_jatah_cuti }} Hari</td>
                @endif
                <td style="padding: 12px; text-align: center;">
                    @if($cuti->status === 'Pending')
                        <span style="background: #fef3c7; color: #d97706; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-hourglass-half" style="margin-right: 4px;"></i> Pending
                        </span>
                    @elseif($cuti->status === 'Disetujui')
                        <span style="background: #d1fae5; color: #059669; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> Disetujui
                        </span>
                    @else
                        <span style="background: #fee2e2; color: #dc2626; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fa-solid fa-circle-xmark" style="margin-right: 4px;"></i> Ditolak
                        </span>
                    @endif
                </td>
                @if(auth()->user()->role === 'admin')
                    <td style="padding: 12px;">
                        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                            @if($cuti->status === 'Pending')
                                <form action="{{ route('cuti.setujui', $cuti->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan cuti ini?')">
                                    @csrf
                                    <button type="submit" class="btn-action" style="background: #10b981; color: white; border-color: #10b981;">
                                        <i class="fa-solid fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('cuti.tolak', $cuti->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan cuti ini?')">
                                    @csrf
                                    <button type="submit" class="btn-action btn-action-danger" style="background: #ef4444; color: white; border-color: #ef4444;">
                                        <i class="fa-solid fa-xmark"></i> Tolak
                                    </button>
                                </form>
                            @else
                                <span style="color: #94a3b8; font-size: 13px; font-style: italic;">Selesai diproses</span>
                            @endif
                        </div>
                    </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 6 }}" style="padding: 20px; text-align: center; color: #94a3b8;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Belum ada pengajuan cuti.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    // Penanganan transisi alert secara halus
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => { successAlert.style.display = 'none'; }, 500);
        }, 3000);
    }
    
    if (errorAlert) {
        setTimeout(() => {
            errorAlert.style.opacity = '0';
            setTimeout(() => { errorAlert.style.display = 'none'; }, 500);
        }, 4000);
    }
</script>
@endsection
