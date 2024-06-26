<?php

namespace App\Http\Requests\Api\Tasks;

use App\Rules\Tasks\ValidParent;
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
            'parent_id' => ['sometimes', 'nullable', new ValidParent],
            'title'     => ['sometimes', 'min:3', 'max:100'],
            'content'   => ['sometimes', 'max:10000'],
            'status_id' => ['sometimes', 'exists:statuses,id'],
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

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (func_num_args() >= 1) {
            return $data;
        }

        $published = filter_var($this->get('publish'), FILTER_VALIDATE_BOOL);
        $task = $this->route('task');

        $data['published_at'] = match(true) {
            $this->has('publish') && $published && ! $task->published_at => now(),
            $this->has('publish') && ! $published && $task->published_at => null,
            default                                                      => $task->published_at,
        };

        return $data;
    }
}
