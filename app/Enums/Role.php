<?php

namespace App\Enums;

enum Role: string
{
    case GURU = 'guru';
    case ORANG_TUA = 'orang_tua';

    public function label(): string
    {
        return match ($this) {
            self::GURU => 'Guru',
            self::ORANG_TUA => 'Orang Tua',
        };
    }
}
