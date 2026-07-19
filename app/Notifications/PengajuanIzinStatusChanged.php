<?php

namespace App\Notifications;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanIzin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanIzinStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly PengajuanIzin $pengajuanIzin,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->pengajuanIzin->status;
        $siswa = $this->pengajuanIzin->siswa;

        $mail = (new MailMessage)
            ->subject("Pengajuan Izin {$status->label()}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Pengajuan izin untuk siswa **{$siswa->nama_siswa}** pada tanggal **{$this->pengajuanIzin->tanggal_izin->format('d/m/Y')}** telah **{$status->label()}**.");

        if ($status === StatusPengajuan::REJECTED) {
            $mail->line('**Alasan penolakan:** Menunggu kehadiran siswa.');
        }

        return $mail
            ->action('Lihat Detail', url('/orang-tua/pengajuan-izin'))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_izin_id' => $this->pengajuanIzin->id,
            'siswa_nama' => $this->pengajuanIzin->siswa->nama_siswa,
            'status' => $this->pengajuanIzin->status->value,
            'tanggal_izin' => $this->pengajuanIzin->tanggal_izin->format('d/m/Y'),
            'message' => "Pengajuan izin {$this->pengajuanIzin->status->label()} untuk {$this->pengajuanIzin->siswa->nama_siswa}",
        ];
    }
}
