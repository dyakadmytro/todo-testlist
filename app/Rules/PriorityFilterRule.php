<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PriorityFilterRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(is_int($value)) {
            $from = intval($value);
            $to = intval($value);
        } else {
            $from = intval($value['from']?? 1);
            $to = intval($value['to']?? 5);
        }

        if ($from < 1 || $from > 5 || $to > 5 || $to < 1) {
            $fail('The :attribute value cant be less than 1 and more than 5.');
        }

        if ($from > $to) {
            $fail('The :attribute from cant be gather than to.');
        }
    }
}
