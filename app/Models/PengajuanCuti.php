<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'jml_hari_cuti',
        'status',
    ];

    /**
     * Get the user that requested the leave.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
