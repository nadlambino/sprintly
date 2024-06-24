<?php

namespace App\Http\Requests\PriorityLevel;

use App\Rules\PriorityLevel\UniqueName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('priority_level'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'min:3', 'max:50', new UniqueName],
            'description' => ['sometimes', 'max:1000'],
            'score'       => ['sometimes', 'integer', 'gt:0', 'lte:10'],
            'color'       => ['sometimes', 'hex_color'],
        ];
    }
}
