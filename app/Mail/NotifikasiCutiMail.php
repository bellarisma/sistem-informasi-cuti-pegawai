<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PengajuanCuti;

class NotifikasiCutiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cuti;
    public $pesanEmail;

    // Menerima data cuti dan pesan kustom
    public function __construct(PengajuanCuti $cuti, $pesanEmail)
    {
        $this->cuti = $cuti;
        $this->pesanEmail = $pesanEmail;
    }

    public function build()
    {
        return $this->subject('Update Pengajuan Cuti - ' . config('app.name'))
                    ->view('emails.notifikasi_cuti');
    }
}