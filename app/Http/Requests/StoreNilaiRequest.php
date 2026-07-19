<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNilaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'exists:siswas,id'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'skor' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Siswa harus dipilih.',
            'siswa_id.exists' => 'Siswa tidak valid.',
            'mata_pelajaran.required' => 'Mata pelajaran wajib diisi.',
            'mata_pelajaran.max' => 'Mata pelajaran maksimal 100 karakter.',
            'skor.required' => 'Skor wajib diisi.',
            'skor.numeric' => 'Skor harus berupa angka.',
            'skor.min' => 'Skor minimal 0.',
            'skor.max' => 'Skor maksimal 100.',
        ];
    }
}
