<?php

namespace App\Rules\PriorityLevel;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueName implements ValidationRule
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

        $currentPriorityLevel = request()->route('priorityLevel');

        $exists = request()->user()->priorityLevels()
            ->where('name', $value)
            ->when($currentPriorityLevel, fn ($query) => $query->where('id', '!=', $currentPriorityLevel->id))
            ->exists();

        if ($exists) {
            $fail('Priority level with this name already exists.');
        }
    }
}
