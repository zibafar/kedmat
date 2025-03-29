<?php

namespace App\Foundation\Traits;

use Illuminate\Support\Arr;

trait TableInfo
{
    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public static function getMorphCallasName()
    {
        return (new self)->getMorphClass();
    }

    public static function getFillables()
    {
        return (new self)->getFillable();
    }

    public static function intersectWithFillable(array $attributes)
    {
        $fillable = self::getFillables();

        if (Arr::isList($attributes)) {
            return array_intersect_key($attributes, $fillable);
        }

        return array_intersect_key($attributes, array_flip($fillable));
    }
}
