<?php

namespace App\Rules\Tasks;

use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidParent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) {
            return;
        }

        $taskId = request()->get('id', null);

        $exists = Task::where('id', $value)
            ->when($taskId, fn ($query) => $query->where('id', '!=', $taskId))
            ->exists();

        if (! $exists) {
            $fail('The selected parent task is not valid.');
        }
    }
}
