@extends('layouts.app') 

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Pegawai</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Selamat Datang, {{ $user->name }}! 👋</li>
    </ol>

    <!-- Baris Kotak Informasi (Sisa Cuti) -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card bg-primary text-white mb-4 shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 small text-uppercase fw-bold">Sisa Jatah Cuti Tahunan</div>
                        {{-- Asumsi kamu punya kolom jatah_cuti di tabel users, kalau belum ada kita tampilkan default dulu --}}
                        <div class="fs-2 fw-bold">{{ $user->jatah_cuti ?? '12' }} Hari</div>
                    </div>
                    <i class="fas fa-calendar-check fa-2x text-white-50"></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ route('cuti.index') }}">Ajukan Cuti Baru</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Cuti Pribadi -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-history me-1"></i>
                <strong>5 Pengajuan Cuti Terakhir Anda</strong>
            </div>
            <a href="{{ route('cuti.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Alasan</th>
                            <th>Status Approval</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatCuti as $key => $cuti)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                            <td>{{ $cuti->alasan }}</td>
                            <td>
                                @if($cuti->status === 'pending')
                                    <span class="badge bg-warning text-dark text-uppercase px-3 py-2">Pending</span>
                                @elseif($cuti->status === 'disetujui')
                                    <span class="badge bg-success text-uppercase px-3 py-2">Disetujui</span>
                                @else
                                    <span class="badge bg-danger text-uppercase px-3 py-2">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Anda belum pernah mengajukan cuti.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection