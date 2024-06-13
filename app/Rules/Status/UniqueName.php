<?php

namespace App\Rules\Status;

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

        $currentStatus = request()->route('status');

        $exists = request()->user()->statuses()
            ->where('name', $value)
            ->when($currentStatus, fn ($query) => $query->where('id', '!=', $currentStatus->id))
            ->exists();

        if ($exists) {
            $fail('Status with this name already exists.');
        }

        $exists = request()->user()->statuses()
            ->onlyTrashed()
            ->where('name', $value)
            ->when($currentStatus, fn ($query) => $query->where('id', '!=', $currentStatus->id))
            ->exists();;

        if ($exists) {
            $fail('Status with this name already exists in your trash. Simply restore it.');
        }
    }
}
