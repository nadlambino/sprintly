<?php

namespace App\Http\Requests\Api\PriorityLevel;

use App\Models\PriorityLevel;
use App\Rules\PriorityLevel\UniqueName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', PriorityLevel::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'min:3', 'max:50', new UniqueName],
            'description' => ['sometimes', 'max:1000'],
            'score'       => ['required', 'integer', 'gt:0', 'lte:10'],
            'color'       => ['required', 'hex_color'],
        ];
    }
}
