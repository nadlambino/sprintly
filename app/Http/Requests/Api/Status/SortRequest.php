<?php

namespace App\Http\Requests\Api\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SortRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('sort', $this->route('status'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_order' => ['required', 'integer', 'gt:0'],
            'old_order' => ['required', 'sometimes', 'integer', 'gt:0'],
        ];
    }
}
