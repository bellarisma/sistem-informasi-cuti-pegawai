@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengajuan Cuti</h1>

    <!-- Notifikasi Sukses / Gagal -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- 1. FORM PENGAJUAN CUTI -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-paper-plane me-1"></i> <strong>Formulir Cuti Baru</strong>
                </div>
                <div class="card-body">
                    <div class="mb-3 bg-light p-2 rounded text-center">
                        <small class="text-muted d-block text-uppercase fw-bold">Sisa Jatah Cuti Anda</small>
                        <span class="fs-4 fw-bold text-primary">{{ $user->sisa_jatah_cuti ?? '12' }} Hari</span>
                    </div>

                    <form action="{{ route('cuti.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                   id="tanggal_mulai" 
                                   name="tanggal_mulai" 
                                   value="{{ old('tanggal_mulai') }}" 
                                   required>
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                   id="tanggal_selesai" 
                                   name="tanggal_selesai" 
                                   value="{{ old('tanggal_selesai') }}" 
                                   required>
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alasan" class="form-label fw-semibold">Alasan Cuti</label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                      id="alasan" 
                                      name="alasan" 
                                      rows="3" 
                                      placeholder="Contoh: Acara keluarga pernikahan adik, Sakit perlu rawat jalan, dll."
                                      required>{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. TABEL RIWAYAT CUTI PRIBADI -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-history me-1"></i> <strong>Riwayat Pengajuan Cuti Anda</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Alasan</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatCuti as $key => $cuti)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                                    <td>{{ $cuti->alasan }}</td>
                                    <td class="text-center">
                                        @if($cuti->status === 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 text-uppercase">
                                                <i class="fas fa-clock me-1"></i> Pending
                                            </span>
                                        @elseif($cuti->status === 'disetujui')
                                            <span class="badge bg-success px-3 py-2 text-uppercase">
                                                <i class="fas fa-check me-1"></i> Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 text-uppercase">
                                                <i class="fas fa-times me-1"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                        Anda belum memiliki riwayat pengajuan cuti.
                                    </td>
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