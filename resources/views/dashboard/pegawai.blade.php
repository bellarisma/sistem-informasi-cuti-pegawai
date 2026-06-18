@extends('layouts.app') {{-- Sesuaikan jika nama layoutmu yang bener itu layouts.backend atau layouts.app --}}

@section('content')
<div class="content-wrapper p-4"> {{-- Bungkus utama agar ada jarak/padding yang rapi --}}
    
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark m-0">Dashboard Pegawai</h1>
            <p class="text-muted">Selamat Datang, {{ $user->name }}! 👋</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white p-3">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 text-white-50 text-uppercase small fw-bold">Sisa Jatah Cuti</p>
                        <h3 class="fw-bold m-0">{{ $user->sisa_jatah_cuti ?? '12' }} Hari</h3>
                    </div>
                    <div class="icon fs-1 opacity-50">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('cuti.index') }}" class="text-white fw-semibold small text-decoration-none">
                        Ajukan Cuti Baru <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12" style="margin-top: 25px;"> {{-- Kasih jarak aman dari kotak atas --}}
            
            <h3 class="fw-bold text-dark mb-3" style="font-size: 1.5rem; display: flex; align-items: center;">
                <i class="fa-solid fa-clock-rotate-left me-2 text-primary" style="font-size: 1.3rem;"></i> 
                5 Pengajuan Cuti Terakhir
            </h3>
            
            <div class="card shadow-sm" style="background: #ffffff; border: 1px solid #eef2f5; border-radius: 15px; padding: 20px;">
                <div class="table-responsive">
                    
                    {{-- Kita pakai CSS inline murni biar tidak ditimpa style Bootstrap yang kaku --}}
                    <table class="table" style="width: 100%; border-collapse: collapse; margin: 0; background: transparent;">
                        <thead>
                            <tr style="border-bottom: 2px solid #f4f6f9;">
                                <th style="padding: 15px 10px; text-align: center; font-weight: 700; color: #000000;">No</th>
                                <th style="padding: 15px 10px; font-weight: 700; color: #000000;">Tanggal Mulai</th>
                                <th style="padding: 15px 10px; font-weight: 700; color: #000000;">Tanggal Selesai</th>
                                <th style="padding: 15px 10px; font-weight: 700; color: #000000;">Alasan</th>
                                <th style="padding: 15px 10px; text-align: center; font-weight: 700; color: #000000;">Status Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse($riwayatCuti as $key => $cuti)
                          <tr style="border-bottom: 1px solid #f4f6f9;">
                              <td style="padding: 18px 10px; text-align: center; font-weight: 600; color: #333;">{{ $key + 1 }}</td>
                              <td style="padding: 18px 10px; color: #333;">{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                              <td style="padding: 18px 10px; color: #333;">{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                              <td style="padding: 18px 10px; color: #555;">"{{ $cuti->alasan }}"</td>
                              <td style="padding: 18px 10px; text-align: center;">
                                  {{-- SINKRONISASI STATUS SESUAI DATABASE --}}
                                  @if($cuti->status === 'pending' || $cuti->status === 'Pending')
                                       <span style="color: #efa31d; font-weight: 700; font-size: 0.9rem;">Pending</span>
                                  @elseif($cuti->status === 'disetujui' || $cuti->status === 'Disetujui')
                                       <span style="color: #2ec4b6; font-weight: 700; font-size: 0.9rem;">Disetujui</span>
                                  @else
                                       <span style="color: #e71d36; font-weight: 700; font-size: 0.9rem;">Ditolak</span>
                                  @endif
                              </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-center; padding: 30px; color: #999;">Kamu belum pernah mengajukan cuti.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    </div>

</div>
@endsection