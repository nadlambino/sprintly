<?php

namespace App\Http\Requests\Api\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'min:3', 'max:100'],
            'description' => ['sometimes', 'nullable', 'max:10000'],
            'status_id'   => ['sometimes', 'nullable', 'int', 'exists:statuses,id'],
            'published'   => ['required', 'boolean']
        ];
    }
}
