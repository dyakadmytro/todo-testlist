<?php

namespace App\Http\Requests;

use App\Models\Task;
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
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (Gate::denies('view', Task::findOrFail(intval($value)))) {
                        $fail("You can`t attach task to this parent task id: {$value}");
                    }
                },
            ],
            'name' => 'required|string|max:32',
            'description' => 'nullable|string|max:255',
            'priority' => 'required|integer|between:1,5',
        ];
    }
}
