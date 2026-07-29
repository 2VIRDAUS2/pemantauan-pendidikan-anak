<?php

namespace App\Jobs;

use App\Enums\StatusPengajuan;
use App\Models\Presensi;
use App\Models\Siswa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RekapAbsensiHarian implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function handle(): void
    {
        $today = now()->toDateString();

        if (now()->isWeekend()) {
            return;
        }

        $allSiswas = Siswa::all();

        foreach ($allSiswas as $siswa) {
            $existing = $siswa->presensis()->whereDate('tanggal', $today)->exists();
            if ($existing) {
                continue;
            }

            $hasIzinApproved = $siswa->pengajuanIzins()
                ->where('tanggal_izin', $today)
                ->where('status', StatusPengajuan::APPROVED)
                ->exists();

            $status = $hasIzinApproved ? 'izin' : 'alpha';

            $siswa->presensis()->create([
                'tanggal' => $today,
                'status' => $status,
            ]);
        }
    }
}
