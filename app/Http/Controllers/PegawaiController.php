<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class  PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = User::where('role', 'pegawai')->get();
        return view('pegawai.index', compact('pegawai'));
    }
    //Tambah Karyawan
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin'){
            abort(403, 'Anda tidak memiliki akses.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'nip' => 'required|unique:users',
            'jabatan' => 'required',
            'divisi' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'divisi' => $request->divisi,
            'role' => 'pegawai',
            'password' => Hash::make('admin123'), //default
        ]);
        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan!');
    }
    //Update Karyawan (Admin)
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses.');
        }
        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'nip', 'jabatan', 'divisi']));

        return redirect()->back()->with('success', 'Data pegawai berhasil diubah!');
    }
    //Hapus Karyawan (Admin)
    public function destroy($id)
    {
    if (auth()->user()->role !== 'admin'){
        abort(403, 'Anda tidak memiliki akses.');
    }
    $user = User::findOrFail($id);

    $user->delete();

    return redirect()->back()->with('success', 'Pegawai berhasil dihapus!');
    }
}