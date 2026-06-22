<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Cuti</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        <div style="border-bottom: 2px solid #eff6ff; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="color: #1e293b; margin: 0;">Sistem Informasi Cuti</h2>
            <p style="color: #64748b; font-size: 14px; margin: 5px 0 0 0;">Notifikasi Aktivitas Akun Anda</p>
        </div>

        <p style="color: #334155; font-size: 16px; line-height: 1.6;">
            {{ $pesanEmail }}
        </p>

        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 20px; border-radius: 8px; margin: 25px 0;">
            <h4 style="margin: 0 0 10px 0; color: #1e293b;">Detail Pengajuan:</h4>
            <table style="width: 100%; font-size: 14px; color: #475569;">
                <tr>
                    <td style="width: 30%; padding: 4px 0; font-weight: 600;">Nama Pegawai</td>
                    <td style="padding: 4px 0;">: {{ $cuti->user->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-weight: 600;">Tanggal Cuti</td>
                    <td style="padding: 4px 0;">: {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-weight: 600;">Durasi</td>
                    <td style="padding: 4px 0;">: {{ $cuti->jml_hari_cuti }} Hari</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-weight: 600;">Alasan</td>
                    <td style="padding: 4px 0;">: {{ $cuti->alasan }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; font-weight: 600;">Status Saat Ini</td>
                    <td style="padding: 4px 0;">: 
                        <strong style="text-transform: uppercase; color: {{ strtolower($cuti->status) === 'disetujui' ? '#15803d' : (strtolower($cuti->status) === 'ditolak' ? '#b91c1c' : '#b45309') }}">
                            {{ $cuti->status }}
                        </strong>
                    </td>
                </tr>
            </table>
        </div>

        <p style="color: #94a3b8; font-size: 12px; text-align: center; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 15px;">
            Email ini dikirim otomatis oleh sistem. Harap tidak membalas email ini.
        </p>
    </div>
</body>
</html>