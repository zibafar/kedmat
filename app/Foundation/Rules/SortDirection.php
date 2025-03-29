<?php

namespace App\Foundation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;
use Illuminate\Translation\PotentiallyTranslatedString;

class SortDirection implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $str=strtolower(trim($value));
        if(!Str::endsWith($str,['_asc','_desc'])){
            $fail('The selection for ascending and descending order is not accurate.');
        }

    }
}