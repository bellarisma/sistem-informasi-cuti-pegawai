@extends('layouts.app')

@section('content')

<div class="topbar">
    <h2>Dashboard</h2>
    <p>Selamat datang di Employee Management System</p>
</div>

<div class="card-grid">

    <div class="card">
        <h3>Total Pegawai</h3>
        <h1>{{ $totalPegawai }}</h1>
    </div>

    <div class="card">
        <h3>Total Pengajuan Cuti</h3>
        <h1>{{ $totalCuti }}</h1>
    </div>

    <div class="card">
        <h3>Total Unit Kerja</h3>
        <h1>{{ $totalUnitKerja }}</h1>
    </div>

</div>

<div class="table-box">
    <h3 style="margin-bottom: 15px;">Pegawai Terbaru</h3>
    @if($pegawaiTerbaru->count() > 0)
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px;">NIP</th>
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Jabatan</th>
                    <th style="padding: 12px;">Divisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawaiTerbaru as $p)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; color: #475569;">{{ $p->nip }}</td>
                    <td style="padding: 12px; font-weight: 500; color: #1e293b;">{{ $p->name }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $p->jabatan }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $p->divisi }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #94a3b8; padding: 10px 0;">Belum ada data pegawai.</p>
    @endif
</div>

@endsection