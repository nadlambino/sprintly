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

        $status = request()->user()->statuses()
            ->withTrashed()
            ->where('name', $value)
            ->when($currentStatus, fn ($query) => $query->where('id', '!=', $currentStatus->id))
            ->first();

        if ($status && ! $status->deleted_at) {
            $fail('Status with this name already exists.');
        } else if ($status && $status->deleted_at) {
            $fail('Status with this name already exists in your trash. Simply restore it.');
        }
    }
}
