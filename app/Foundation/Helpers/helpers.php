<?php

use Illuminate\Support\Str;

if (!function_exists('extractSort')) {

    function extractSort(string $param): array
    {
        return [
            'column' => Str::beforeLast($param, '_'),
            'direction' => Str::afterLast($param, '_'),
        ];
    }
}

