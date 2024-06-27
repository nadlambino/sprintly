<?php

namespace App\Http\Requests\Api\Task;

use App\Rules\Status\OwnedBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TransitionRequest extends FormRequest
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
            'status_id' => ['required_without:direction', new OwnedBy($this->user())],
            'direction' => ['required_without:status_id', 'in:backward,forward'],
        ];
    }

    protected function prepareForValidation()
    {
        $task = $this->route('task');

        $statusId = match (true) {
            $this->get('status_id')                => $this->get('status_id'),
            $this->get('direction') === 'forward'  => $task->status->next()->first()?->id,
            $this->get('direction') === 'backward' => $task->status->previous()->first()?->id,
            default                                => null,
        };

        $this->getInputSource()->replace([
            'status_id' => $statusId
        ]);

        return parent::getValidatorInstance();
    }
}
