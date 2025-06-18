<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadOrders extends FormRequest
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
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'csv.required' => 'Please upload a CSV file.',
            'csv.file' => 'The uploaded file must be a valid file.',
            'csv.mimes' => 'The file must be of type: csv or txt.',
            'csv.max' => 'The file may not be greater than 2MB.',
        ];
    }
}
