<?php

namespace App\Http\Requests;

use App\Rules\PriorityFilterRule;
use Illuminate\Foundation\Http\FormRequest;

class GetTasksRequest extends FormRequest
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
            'filters.status' => 'in:todo,done',
            'filters.priority' => new PriorityFilterRule,
            'filters.priority.from' => 'integer|min:1|max:5',
            'filters.priority.to' => 'integer|min:1|max:5',
            'filters.title' => 'string|max:32',
        ];
    }
}
