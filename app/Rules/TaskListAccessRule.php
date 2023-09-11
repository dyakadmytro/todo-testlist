<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class TaskListAccessRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tl = Auth::user()->taskLists()->where('id', intval($value))
            ->first();
        if(!$tl) {
            $fail("You are not allowed to task list by id: {$value}");
        }
    }
}
