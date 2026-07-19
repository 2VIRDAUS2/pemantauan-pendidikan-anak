<?php

namespace App\Http\Requests;

use App\Enums\StatusPengajuan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifikasiIzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([StatusPengajuan::APPROVED->value, StatusPengajuan::REJECTED->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status verifikasi wajib ditentukan.',
            'status.in' => 'Status harus disetujui atau ditolak.',
        ];
    }
}
