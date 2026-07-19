<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanIzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'exists:siswas,id'],
            'tanggal_izin' => ['required', 'date', 'after_or_equal:today'],
            'alasan' => ['required', 'string', 'min:10', 'max:500'],
            'file_bukti' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa harus dipilih.',
            'siswa_id.exists' => 'Siswa tidak valid.',
            'tanggal_izin.required' => 'Tanggal izin wajib diisi.',
            'tanggal_izin.after_or_equal' => 'Tanggal izin harus hari ini atau di masa depan.',
            'alasan.required' => 'Alasan izin wajib diisi.',
            'alasan.min' => 'Alasan izin minimal 10 karakter.',
            'alasan.max' => 'Alasan izin maksimal 500 karakter.',
            'file_bukti.required' => 'Bukti dokumen wajib diunggah.',
            'file_bukti.mimes' => 'Format file harus pdf, jpg, jpeg, atau png.',
            'file_bukti.max' => 'Ukuran file maksimal 5MB.',
        ];
    }
}
