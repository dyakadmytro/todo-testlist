<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Rules\ParentAccessRule;
use App\Rules\TaskListAccessRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTaskRequest extends FormRequest
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
                'nullable',
                'exists:tasks,id',
                new ParentAccessRule()
            ],
            'title' => 'required|string|max:32',
            'task_list_id' => [
                'required',
                'integer',
                'exists:task_lists,id',
                new TaskListAccessRule()
            ],
            'description' => 'nullable|string|max:255',
            'priority' => 'required|integer|between:1,5',
        ];
    }
}
