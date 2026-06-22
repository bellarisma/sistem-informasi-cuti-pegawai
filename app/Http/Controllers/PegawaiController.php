<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar pegawai dan admin (Terpisah)
     */
    public function index()
    {
        // Pisahkan data pegawai biasa dan data admin
        $daftarPegawai = User::where('role', 'pegawai')->get();
        $daftarAdmin = User::where('role', 'admin')->get();

        return view('pegawai.index', compact('daftarPegawai', 'daftarAdmin'));
    }

    /**
     * TAMPILKAN FORM TAMBAH (Pegawai / Admin)
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('pegawai.create');
    }

    /**
     * PROSES SIMPAN DATA (Pegawai / Admin)
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin'){
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Validasi dasar untuk semua jenis user
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'nip' => 'required|unique:users',
            'role' => 'required|string|in:pegawai,admin',
        ];

        // Kalau yang ditambah adalah pegawai biasa, jabatan dan divisi wajib diisi
        if ($request->role === 'pegawai') {
            $rules['jabatan'] = 'required';
            $rules['divisi'] = 'required';
        }

        $request->validate($rules);

        // Tentukan nilai berdasarkan role yang dipilih
        $roleInput = $request->role;
        $divisiInput = $roleInput === 'admin' ? 'Administrator' : $request->divisi;
        $jabatanInput = $roleInput === 'admin' ? 'Admin Sistem' : $request->jabatan;
        $cutiDefault = $roleInput === 'admin' ? 0 : 12; // Admin tidak punya jatah cuti, pegawai default 12

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $jabatanInput,
            'divisi' => $divisiInput,
            'role' => $roleInput,
            'sisa_jatah_cuti' => $cutiDefault,
            'password' => Hash::make('admin123'), // Default password untuk user baru
        ]);

        $pesan = $roleInput === 'admin' ? 'Admin baru berhasil ditambahkan!' : 'Pegawai berhasil ditambahkan!';
        
        return redirect()->route('pegawai.index')->with('success', $pesan);
    }

    /**
     * TAMPILKAN FORM EDIT KARYAWAN / ADMIN
     */
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $pegawai = User::findOrFail($id);
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * PROSES UPDATE DATA KARYAWAN / ADMIN (Oleh Admin)
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        // Kumpulkan data yang mau diupdate
        $dataUpdate = [
            'name'  => $request->name,
            'nip'   => $request->nip,
            'email' => $request->email,
        ];

        // Kalau yang diedit adalah pegawai biasa, update juga jabatan, divisi, dan sisa cuti jika ada
        if ($user->role === 'pegawai') {
            $dataUpdate['jabatan'] = $request->jabatan;
            $dataUpdate['divisi']  = $request->divisi;
            if ($request->has('sisa_jatah_cuti')) {
                $dataUpdate['sisa_jatah_cuti'] = $request->sisa_jatah_cuti;
            }
        }

        $user->update($dataUpdate);

        return redirect()->route('pegawai.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * PROSES HAPUS KARYAWAN / ADMIN
     */
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin'){
            abort(403, 'Anda tidak memiliki akses.');
        }
        
        // Mencegah admin menghapus dirinya sendiri jika tidak sengaja menembak rute destroy
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus!');
    }

    /**
     * Menampilkan halaman profil pintar (Bisa diakses user yang sedang login)
     */
    public function editProfil()
    {
        $user = auth()->user(); 
        return view('pegawai.profil', compact('user'));
    }

    /**
     * Memproses update data dari halaman profil pintar
     */
    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed', 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Admin bisa ubah data divisinya sendiri lewat profil
        if ($user->role === 'admin' && $request->has('divisi')) {
            $user->divisi = $request->divisi;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}