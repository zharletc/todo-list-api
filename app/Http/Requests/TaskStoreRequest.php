<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'time_tracked' => 'nullable|integer',
            'status' => 'nullable|in:pending,open,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? 'pending',
            'time_tracked' => $this->time_tracked ?? 0
        ]);
    }
}
