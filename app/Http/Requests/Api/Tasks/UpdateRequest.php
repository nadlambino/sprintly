<?php

namespace App\Http\Requests\Api\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('task'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'     => ['required', 'min:3', 'max:100', 'unique:tasks,title,' . $this->route('task')->id],
            'content'   => ['required', 'min:3', 'max:10000'],
            'status_id' => ['required', 'exists:statuses,id'],
        ];
    }
}
