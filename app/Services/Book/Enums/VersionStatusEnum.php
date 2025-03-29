<?php

namespace App\Services\Book\Enums;

use App\Foundation\Traits\PowerEnum;

enum VersionStatusEnum: int
{
    use PowerEnum;

    case AVAILABLE = 0;
    case DAMAGED = 1;
    case RESERVED = 2;


    public static function setLabels(): array
    {
        return [
            self::AVAILABLE->value => 'available',
            self::DAMAGED->value => 'damaged',
            self::RESERVED->value => 'reserved',
        ];
    }
}
