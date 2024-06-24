<?php

namespace App\Rules\Task;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPriorityLevel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        $exists = request()->user()->priorityLevels()->where('id', $value)->exists();

        if (!$exists) {
            $fail('Priority level does not exist.');
        }
    }
}
