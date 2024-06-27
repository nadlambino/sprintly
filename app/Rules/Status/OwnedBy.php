<?php

namespace App\Rules\Status;

use App\Models\User;
use App\QueryBuilders\Status\StatusBuilder;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnedBy implements ValidationRule
{
    public function __construct(protected User $user) { }

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

        $exists = StatusBuilder::make()
            ->of($this->user)
            ->notFromRequest()
            ->build()
            ->exists();

        if (! $exists) {
            $fail('Status does not exist.');
        }
    }
}
