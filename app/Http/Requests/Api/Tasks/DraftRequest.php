<?php

namespace App\Http\Requests\Api\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class DraftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (request('id') === null || request()->user()->tasks()->where('id', request('id'))->exists());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id'        => ['sometimes', 'nullable', 'exists:tasks,id'],
            'title'     => ['required', 'min:3', 'max:100', 'unique:tasks,title'],
            'content'   => ['required', 'min:3', 'max:10000'],
            'status_id' => ['required', 'exists:statuses,id'],
            'draft'     => ['required', 'boolean']
        ];
    }
}
