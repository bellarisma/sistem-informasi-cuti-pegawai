@extends('layouts.app')

@section('content')
<div class="topbar">
    <h2>Manajemen Data Pengguna</h2>
    <p>Kelola data akun pegawai biasa maupun sesama administrator sistem di sini.</p>
</div>

{{-- Notifikasi Sukses --}}
@if(session('success'))
    <div style="background: #dcfce7; color: #15803d; padding: 12px 15px; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 20px; font-weight: 500;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- ========================================================= --}}
{{-- TABEL 1: DAFTAR PEGAWAI / KARYAWAN BIASA                 --}}
{{-- ========================================================= --}}
<div class="table-box" style="margin-bottom: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #1e293b; font-weight: 600;"><i class="fa-solid fa-users" style="color: #2563eb; margin-right: 8px;"></i> Daftar Pegawai</h3>
        <button class="btn-custom btn-primary" onclick="openModalTambah('pegawai')">
            <i class="fa-solid fa-user-plus"></i> Tambah Pegawai
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #64748b;">
                <th style="padding: 12px;">Nama</th>
                <th style="padding: 12px;">Email</th>
                <th style="padding: 12px;">Divisi</th>
                <th style="padding: 12px; text-align: center;">Sisa Jatar Cuti</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarPegawai as $p)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px; font-weight: 500; color: #1e293b;">{{ $p->name }}</td>
                <td style="padding: 12px; color: #475569;">{{ $p->email }}</td>
                <td style="padding: 12px;"><span style="background: #eff6ff; color: #1e6091; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 500;">{{ $p->divisi ?? '-' }}</span></td>
                <td style="padding: 12px; text-align: center; font-weight: 600; color: #059669;">{{ $p->sisa_jatah_cuti }} Hari</td>
                <td style="padding: 12px; text-align: center;">
                    <button class="btn-action" onclick="openModalEdit({{ json_encode($p) }})"><i class="fa-solid fa-pen-to-square"></i></button>
                    <form id="delete-form-{{ $p->id }}" action="{{ route('pegawai.destroy', $p->id) }}" method="POST" style="display:inline;">
                @csrf 
                    @method('DELETE')
                     <button type="button" class="btn-action btn-action-danger" onclick="konfirmasiHapus('{{ $p->id }}', '{{ $p->name }}')">
                  <i class="fa-solid fa-trash"></i>
                </button>
            </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 20px; text-align: center; color: #94a3b8;">Belum ada data pegawai biasa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ========================================================= --}}
{{-- TABEL 2: DAFTAR ADMIN SISTEM (KHUSUS AKUN ADMIN)         --}}
{{-- ========================================================= --}}
<div class="table-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #1e293b; font-weight: 600;"><i class="fa-solid fa-user-shield" style="color: #dc2626; margin-right: 8px;"></i> Daftar Administrator Sistem</h3>
        <button class="btn-custom btn-secondary" onclick="openModalTambah('admin')" style="background: #1e293b;">
            <i class="fa-solid fa-user-lock"></i> Tambah Admin Baru
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #64748b;">
                <th style="padding: 12px;">Nama Admin</th>
                <th style="padding: 12px;">Email Resmi</th>
                <th style="padding: 12px;">Hak Akses Role</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarAdmin as $admin)
            <tr style="border-bottom: 1px solid #e2e8f0; background: #fffdfd;">
                <td style="padding: 12px; font-weight: 500; color: #1e293b;">{{ $admin->name }}</td>
                <td style="padding: 12px; color: #475569;">{{ $admin->email }}</td>
                <td style="padding: 12px;"><span style="background: #fef2f2; color: #991b1b; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase;">{{ $admin->role }}</span></td>
                <td style="padding: 12px; text-align: center;">
                    @if($admin->id !== auth()->id())
                        <button class="btn-action" onclick="openModalEdit({{ json_encode($admin) }})"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form id="delete-form-{{ $admin->id }}" action="{{ route('pegawai.destroy', $admin->id) }}" method="POST" style="display:inline;">
                    @csrf 
                        @method('DELETE')
                            <button type="button" class="btn-action btn-action-danger" onclick="konfirmasiHapus('{{ $admin->id }}', '{{ $admin->name }}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
                    @else
                        <span style="font-size: 12px; color: #94a3b8; font-style: italic;">Anda Sedang Login</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 20px; text-align: center; color: #94a3b8;">Belum ada akun admin tambahan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ========================================================= --}}
{{-- MODAL BOX 1: FORM TAMBAH (PEGAWAI / ADMIN)                --}}
{{-- ========================================================= --}}
<div id="modalTambah" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3 id="judulModalTambah" style="margin-top: 0; color: #1e293b; margin-bottom: 20px;">Tambah Pengguna</h3>
        
        <form action="{{ route('pegawai.store') }}" method="POST">
            @csrf
            <input type="hidden" name="role" id="tambah_role">

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Nama Lengkap</label>
                <input type="text" name="name" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Email</label>
                <input type="email" name="email" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">NIP</label>
                <input type="text" name="nip" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>

            {{-- Kolom spesifik Pegawai (Akan disembunyikan otomatis via JS jika memilih Tambah Admin) --}}
            <div id="kolomKhususPegawai">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Jabatan</label>
                    <input type="text" name="jabatan" id="tambah_jabatan" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Divisi</label>
                    <select name="divisi" id="tambah_divisi" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; background:white;">
                        <option value="">-- Pilih Divisi --</option>
                        <option value="IT Support">IT Support</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Finance">Finance</option>
                        <option value="HRD">HRD</option>
                    </select>
                </div>
            </div>

            <p style="font-size: 12px; color: #64748b; background: #f1f5f9; padding: 8px; border-radius: 6px;">
                * Password default akun baru adalah <strong>admin123</strong>
            </p>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal('modalTambah')" style="background:#64748b; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-custom btn-primary" style="padding:8px 16px;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================= --}}
{{-- MODAL BOX 2: FORM EDIT (PEGAWAI / ADMIN)                  --}}
{{-- ========================================================= --}}
<div id="modalEdit" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 9999;">
    <div style="background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; color: #1e293b; margin-bottom: 20px;">Edit Data Pengguna</h3>
        
        <form id="formEditPegawai" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Nama Lengkap</label>
                <input type="text" name="name" id="edit_name" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Email</label>
                <input type="email" name="email" id="edit_email" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">NIP</label>
                <input type="text" name="nip" id="edit_nip" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;" required>
            </div>

            {{-- Kolom Edit Tambahan Khusus Pegawai --}}
            <div id="kolomEditKhususPegawai">
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Jabatan</label>
                    <input type="text" name="jabatan" id="edit_jabatan" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Divisi</label>
                    <select name="divisi" id="edit_divisi" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; background:white;">
                        <option value="IT Support">IT Support</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Finance">Finance</option>
                        <option value="HRD">HRD</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display:block; font-weight:500; margin-bottom:5px; font-size:14px;">Sisa Jatah Cuti</label>
                    <input type="number" name="sisa_jatah_cuti" id="edit_cuti" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px;">
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeModal('modalEdit')" style="background:#64748b; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">Batal</button>
                <button type="submit" class="btn-custom btn-primary" style="padding:8px 16px;">Perbarui</button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================= --}}
{{-- JAVASCRIPT LOGIC UNTUK PENGATURAN MODAL POPOP              --}}
{{-- ========================================================= --}}
<script>
    // 1. Fungsi Membuka Modal Tambah (Dinamis Pegawai/Admin)
    function openModalTambah(role) {
    document.getElementById('tambah_role').value = role;
    
    if(role === 'admin') {
        document.getElementById('judulModalTambah').innerText = 'Tambah Administrator Baru';
        
        // Tampilkan kolom divisi, tapi sembunyikan inputan jabatan manual
        document.getElementById('kolomKhususPegawai').style.display = 'block'; 
        document.getElementById('tambah_jabatan').parentElement.style.display = 'none'; // Sembunyikan input jabatan
        
        document.getElementById('tambah_jabatan').required = false;
        document.getElementById('tambah_divisi').required = true; // Divisi tetap wajib pilih
    } else {
        document.getElementById('judulModalTambah').innerText = 'Tambah Pegawai Baru';
        
        // Tampilkan semua untuk pegawai biasa
        document.getElementById('kolomKhususPegawai').style.display = 'block';
        document.getElementById('tambah_jabatan').parentElement.style.display = 'block'; // Munculkan lagi jabatan
        
        document.getElementById('tambah_jabatan').required = true;
        document.getElementById('tambah_divisi').required = true;
    }
    
    document.getElementById('modalTambah').style.display = 'flex';
}

    // 2. Fungsi Membuka Modal Edit (Mengambil dan menyuntikkan data lama otomatis)
    function openModalEdit(userData) {
        // Set action URL form secara dinamis mengarah ke ID user terkait
        document.getElementById('formEditPegawai').action = '/pegawai/' + userData.id;

        // Tembakkan data dasar
        document.getElementById('edit_name').value = userData.name;
        document.getElementById('edit_email').value = userData.email;
        document.getElementById('edit_nip').value = userData.nip;

        // Cek jika yang diedit admin atau pegawai
        if(userData.role === 'admin') {
            document.getElementById('kolomEditKhususPegawai').style.display = 'none';
            document.getElementById('edit_jabatan').required = false;
            document.getElementById('edit_divisi').required = false;
            document.getElementById('edit_cuti').required = false;
        } else {
            document.getElementById('kolomEditKhususPegawai').style.display = 'block';
            document.getElementById('edit_jabatan').value = userData.jabatan || '';
            document.getElementById('edit_divisi').value = userData.divisi || '';
            document.getElementById('edit_cuti').value = userData.sisa_jatah_cuti || 0;
            
            document.getElementById('edit_jabatan').required = true;
            document.getElementById('edit_divisi').required = true;
            document.getElementById('edit_cuti').required = true;
        }

        document.getElementById('modalEdit').style.display = 'flex';
    }

    // 3. Fungsi Menutup Modal
    function closeModal(idModal) {
        document.getElementById(idModal).style.display = 'none';
    }

    // =====================================================================
    // TAMBAHAN BARU - 4. Fungsi Konfirmasi Hapus Menggunakan SweetAlert2
    // =====================================================================
    function konfirmasiHapus(userId, userName) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Akun atas nama " + userName + " akan dihapus permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Warna merah modern
            cancelButtonColor: '#64748b',  // Warna abu-abu slate
            confirmButtonText: 'Ya, Hapus Akun!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya", submit form secara otomatis sesuai ID target
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }
</script>
@endsection