<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndPegawaiFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pegawai'); // admin atau pegawai
            $table->string('nip')->nullable()->unique(); // Nomor Induk Pegawai
            $table->string('jabatan')->nullable();
            $table->string('divisi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kembali kolom jika dilakukan rollback
            $table->dropColumn(['role', 'nip', 'jabatan', 'divisi']);
        });
    }
};
