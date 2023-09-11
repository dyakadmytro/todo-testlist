<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Rules\ParentAccessRule;
use App\Rules\TaskListAccessRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'parent' => [
                'exists:tasks,id',
                new ParentAccessRule(),
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($this->route('task')->id == $value) {
                        $fail("Task can`t be parent for itself");
                    }
                },
            ],
            'title' => 'string|max:32',
            'description' => 'string|max:255',
            'task_list_id' => [
                'integer',
                'exists:task_lists,id',
                new TaskListAccessRule()
            ],
            'priority' => 'integer|between:1,5',
        ];
    }
}
