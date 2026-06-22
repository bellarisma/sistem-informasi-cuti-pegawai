@extends('layouts.app')

@section('content')
<div class="topbar" style="margin-bottom: 25px;">
    <h2>Pengaturan Profil Akun</h2>
    <p>Kelola informasi pribadi Anda dan amankan akun dengan memperbarui password secara berkala.</p>
</div>

@if(session('success'))
    <div style="background: #dcfce7; color: #15803d; padding: 12px 15px; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 20px; font-weight: 500;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<div class="table-box" style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 700px;">
    <form action="{{ route('profil.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- BARIS 1: NAMA & EMAIL --}}
        <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box;">
                @error('name') <span style="color: #b91c1c; font-size: 13px;">{{ $message }}</span> @enderror
            </div>
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box;">
                @error('email') <span style="color: #b91c1c; font-size: 13px;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- BARIS 2: DIVISI & JATAH CUTI (LOGIKA PINTAR) --}}
        <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Divisi / Departemen</label>
                {{-- Jika pegawai, input ini dikunci dan berubah warna kelabu --}}
                <input type="text" name="divisi" value="{{ old('divisi', $user->divisi ?? '-') }}" 
                    {{ $user->role !== 'admin' ? 'disabled' : '' }}
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box; background: {{ $user->role !== 'admin' ? '#f1f5f9' : 'white' }}; color: {{ $user->role !== 'admin' ? '#64748b' : '#1e293b' }};">
                @if($user->role !== 'admin')
                    <span style="font-size: 12px; color: #94a3b8; margin-top: 4px; display: block;">*Perubahan divisi hanya bisa dilakukan oleh Admin.</span>
                @endif
            </div>
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Sisa Jatah Cuti</label>
                <input type="number" name="sisa_jatah_cuti" value="{{ old('sisa_jatah_cuti', $user->sisa_jatah_cuti) }}" 
                    {{ $user->role !== 'admin' ? 'disabled' : '' }}
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box; background: {{ $user->role !== 'admin' ? '#f1f5f9' : 'white' }}; color: {{ $user->role !== 'admin' ? '#64748b' : '#1e293b' }};">
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        <h4 style="margin: 0 0 5px 0; color: #1e293b;">Ganti Password (Opsional)</h4>
        <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Kosongkan kolom di bawah ini jika Anda tidak ingin mengubah password akun Anda.</p>

        {{-- BARIS 3: PASSWORD BARU --}}
        <div style="display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Password Baru</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box;">
                @error('password') <span style="color: #b91c1c; font-size: 13px;">{{ $message }}</span> @enderror
            </div>
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #475569;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                    style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; box-sizing: border-box;">
            </div>
        </div>

        {{-- TOMBOL SIMPAN --}}
        <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection