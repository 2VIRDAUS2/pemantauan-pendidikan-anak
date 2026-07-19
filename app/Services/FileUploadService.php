<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function toArray(UploadedFile $file): array
    {
        $maxSize = 5 * 1024 * 1024;

        if ($file->getSize() > $maxSize) {
            throw new \RuntimeException('Ukuran file maksimal 5MB.');
        }

        return [
            'file_bukti_data' => base64_encode(file_get_contents($file->getRealPath())),
            'file_bukti_nama' => $file->getClientOriginalName(),
            'file_bukti_mime' => $file->getMimeType(),
        ];
    }
}
