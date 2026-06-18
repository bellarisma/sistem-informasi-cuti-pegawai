@extends('layouts.app')

@section('content')
<div class="topbar">
    <h2>Data Pegawai</h2>
    <p>Kelola data informasi seluruh pegawai perusahaan di sini.</p>
</div>

@if(session('success'))
    <div id="success-alert" style="background: #10b981; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; transition: opacity 0.5s ease;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

@if(auth()->user()->role == 'admin')
<div class="table-box" style="margin-bottom: 25px;">
    <h3 style="margin-bottom: 20px;">Tambah Pegawai Baru</h3>
    <form action="{{ route('pegawai.store') }}" method="POST">
        @csrf
        <div class="form-line">
            <label>NIP :</label>
            <input type="text" name="nip" class="input-field" placeholder="Masukkan NIP" required>
        </div>
        <div class="form-line">
            <label>Nama :</label>
            <input type="text" name="name" class="input-field" placeholder="Masukkan Nama Lengkap" required>
        </div>
        <div class="form-line">
            <label>Email :</label>
            <input type="email" name="email" class="input-field" placeholder="Masukkan Email" required>
        </div>
        <div class="form-line">
            <label>Jabatan :</label>
            <select name="jabatan" class="input-field" required>
                <option value="" disabled selected>Pilih Jabatan</option>
                <option value="Staff IT">Staff IT</option>
                <option value="HRD Manager">HRD Manager</option>
                <option value="Finance Officer">Finance Officer</option>
                <option value="Marketing Specialist">Marketing Specialist</option>
            </select>
        </div>
        <div class="form-line">
            <label>Divisi :</label>
            <select name="divisi" class="input-field" required>
                <option value="" disabled selected>Pilih Divisi</option>
                <option value="IT Support">IT Support</option>
                <option value="HRD">HRD</option>
                <option value="Finance">Finance</option>
                <option value="Marketing">Marketing</option>
            </select>
        </div>
        <div class="form-line" style="margin-left: 120px; margin-top: 20px;">
            <button type="submit" class="btn-custom btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endif

<div class="table-box">
    <h3>Daftar Karyawan</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; text-align: left;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 12px;">NIP</th>
                <th style="padding: 12px;">Nama</th>
                <th style="padding: 12px;">Jabatan</th>
                <th style="padding: 12px;">Divisi</th>
                @if(auth()->user()->role == 'admin')
                <th style="padding: 12px; text-align: center;">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $p)
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px;">{{ $p->nip }}</td>
                <td style="padding: 12px;">{{ $p->name }}</td>
                <td style="padding: 12px;">{{ $p->jabatan }}</td>
                <td style="padding: 12px;">{{ $p->divisi }}</td>
                @if(auth()->user()->role == 'admin')
                    <td style="padding: 12px; display: flex; gap: 8px; justify-content: center;">
                     <button class="btn-action" 
                      onclick="openEditModal('{{ $p->id }}', '{{ $p->nip }}', '{{ $p->name }}', '{{ $p->jabatan }}', '{{ $p->divisi }}')">
                     <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>

             <form action="{{ route('pegawai.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
               @csrf
          @method('DELETE')
                <button type="submit" class="btn-action btn-action-danger">
                <i class="fa-solid fa-trash"></i> Hapus
             </button>
         </form>
    </td>
    @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal" id="editModal">
    <div class="modal-content">
        <h3 style="margin-bottom: 20px;">Edit Data Pegawai</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-line">
                <label>NIP :</label>
                <input type="text" name="nip" id="edit_nip" class="input-field" required>
            </div>
            <div class="form-line">
                <label>Nama :</label>
                <input type="text" name="name" id="edit_name" class="input-field" required>
            </div>
            <div class="form-line">
                <label>Jabatan :</label>
                <select name="jabatan" id="edit_jabatan" class="input-field" required>
                    <option value="Staff IT">Staff IT</option>
                    <option value="HRD Manager">HRD Manager</option>
                    <option value="Finance Officer">Finance Officer</option>
                    <option value="Marketing Specialist">Marketing Specialist</option>
                </select>
            </div>
            <div class="form-line">
                <label>Divisi :</label>
                <select name="divisi" id="edit_divisi" class="input-field" required>
                    <option value="IT Support">IT Support</option>
                    <option value="HRD">HRD</option>
                    <option value="Finance">Finance</option>
                    <option value="Marketing">Marketing</option>
                </select>
            </div>
            
            <div class="form-line" style="margin-left: 120px; margin-top: 25px; gap: 10px;">
                <button type="submit" class="btn-custom btn-primary">Update</button>
                <button type="button" class="btn-custom btn-secondary" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = '0'; // Membuat transisi transparan
            setTimeout(() => {
                alertBox.style.display = 'none'; // Menghilangkan elemen dari layout
            }, 500); // Menunggu transisi fade-out selesai (0.5 detik)
        }, 3000); // Muncul selama 3 detik
    }
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');

    function openEditModal(id, nip, name, jabatan, divisi) {
        // Set URL Action form agar mengarah ke id yang tepat
        editForm.action = `/pegawai/${id}`;
        
        // Isi value modal input dengan data baris yang di klik
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_jabatan').value = jabatan;
        document.getElementById('edit_divisi').value = divisi;

        // Tampilkan modal
        editModal.style.display = 'flex';
    }

    function closeEditModal() {
        editModal.style.display = 'none';
    }

    // Menutup modal jika klik di luar box modal
    window.onclick = function(event) {
        if (event.target == editModal) {
            closeEditModal();
        }
    }
</script>
@endsection