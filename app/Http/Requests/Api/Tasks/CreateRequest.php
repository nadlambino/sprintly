<?php

namespace App\Http\Requests\Api\Tasks;

use App\Rules\Tasks\ValidParent;
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
            'parent_id' => ['sometimes', 'nullable', new ValidParent],
            'title'     => ['required', 'min:3', 'max:100'],
            'content'   => ['sometimes', 'max:10000'],
            'status_id' => ['required', 'exists:statuses,id'],
            'start_at'  => ['sometimes', 'nullable', 'date'],
            'due_at'    => ['sometimes', 'nullable', 'date', 'after:start_at'],
            'images'    => ['array'],
            'images.*'  => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.mimes' => 'Image file should be jpeg, png, jpg, gif, svg or webp',
            'images.*' => 'Image is unreadable. Please try a different image.',
        ];
    }
}
