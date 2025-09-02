<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'username' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|regex:/^[0-9+\-\(\)\s]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'username is required',
            'username.max' => 'username must be less than 255 characters',
            'phone_number.required' => 'phone number is required',
            'phone_number.regex' => 'wrong phone number format',
            'phone_number.max' => 'phone number must be less than 20 characters',
        ];
    }

    /**
     * Custom validation handling
     */
    public function validate()
    {
        $instance = $this->getValidatorInstance();
        if ($instance->fails()) {
            $this->failedValidation($instance);
        }
        return $this->validated();
    }
}
