<?php

namespace App\Http\Requests\Api\Status;

use App\Models\Status;
use App\Rules\Status\UniqueName;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', Status::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'min:3', 'max:50', new UniqueName],
            'description'   => ['sometimes', 'max:1000'],
            'color'         => ['required', 'hex_color'],
        ];
    }
}
