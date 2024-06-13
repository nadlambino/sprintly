<?php

namespace App\Http\Requests\Api\Status;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('update', $this->route('status'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'min:3', 'max:50', 'unique:statuses,name'],
            'description'   => ['sometimes', 'max:1000'],
            'color'         => ['sometimes', 'hex_color'],
            'order'         => ['sometimes', 'numeric', 'gte:0'],
        ];
    }
}
