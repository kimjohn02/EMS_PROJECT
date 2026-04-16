<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $employee = $this->route('employee');
        $userId = $employee ? $employee->user_id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'date_hired' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,on_leave',
        ];
    }
}
