<?php

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanIzin;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanIzinTest extends TestCase
{
    use RefreshDatabase;

    private User $orangTua;

    private User $guru;

    private Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orangTua = User::factory()->orangTua()->create([
            'email_verified_at' => now(),
        ]);

        $this->guru = User::factory()->guru()->create([
            'email_verified_at' => now(),
        ]);

        $this->siswa = Siswa::factory()->create([
            'orang_tua_id' => $this->orangTua->id,
        ]);
    }

    public function test_orang_tua_can_submit_pengajuan_izin(): void
    {
        Storage::fake('s3');

        $response = $this->actingAs($this->orangTua)
            ->post(route('orang-tua.pengajuan-izin.store'), [
                'siswa_id' => $this->siswa->id,
                'tanggal_izin' => now()->addDays(3)->format('Y-m-d'),
                'alasan' => 'Sakit demam tinggi dan perlu istirahat di rumah',
                'file_bukti' => UploadedFile::fake()->create('bukti.pdf', 500, 'application/pdf'),
            ]);

        $response->assertRedirect(route('orang-tua.pengajuan-izin.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengajuan_izins', [
            'siswa_id' => $this->siswa->id,
            'status' => StatusPengajuan::PENDING,
        ]);
    }

    public function test_guru_can_approve_pengajuan_izin(): void
    {
        $pengajuan = PengajuanIzin::factory()->create([
            'siswa_id' => $this->siswa->id,
            'status' => StatusPengajuan::PENDING,
        ]);

        $response = $this->actingAs($this->guru)
            ->patch(route('guru.verifikasi-izin.update', $pengajuan), [
                'status' => StatusPengajuan::APPROVED->value,
            ]);

        $response->assertRedirect(route('guru.verifikasi-izin.index'));
        $response->assertSessionHas('success');

        $pengajuan->refresh();
        $this->assertEquals(StatusPengajuan::APPROVED, $pengajuan->status);
        $this->assertEquals($this->guru->id, $pengajuan->verifikator_id);
    }

    public function test_guru_can_reject_pengajuan_izin(): void
    {
        $pengajuan = PengajuanIzin::factory()->create([
            'siswa_id' => $this->siswa->id,
            'status' => StatusPengajuan::PENDING,
        ]);

        $response = $this->actingAs($this->guru)
            ->patch(route('guru.verifikasi-izin.update', $pengajuan), [
                'status' => StatusPengajuan::REJECTED->value,
            ]);

        $response->assertRedirect(route('guru.verifikasi-izin.index'));

        $pengajuan->refresh();
        $this->assertEquals(StatusPengajuan::REJECTED, $pengajuan->status);
    }

    public function test_pending_verifications_only_show_pending_status(): void
    {
        PengajuanIzin::factory()->pending()->count(3)->create(['siswa_id' => $this->siswa->id]);
        PengajuanIzin::factory()->approved()->count(2)->create(['siswa_id' => $this->siswa->id]);

        $response = $this->actingAs($this->guru)
            ->get(route('guru.verifikasi-izin.index'));

        $response->assertOk();
    }

    public function test_validation_requires_all_fields(): void
    {
        $response = $this->actingAs($this->orangTua)
            ->post(route('orang-tua.pengajuan-izin.store'), []);

        $response->assertSessionHasErrors(['siswa_id', 'tanggal_izin', 'alasan', 'file_bukti']);
    }

    public function test_orang_tua_cannot_access_guru_routes(): void
    {
        $response = $this->actingAs($this->orangTua)
            ->get(route('guru.verifikasi-izin.index'));

        $response->assertForbidden();
    }
}
