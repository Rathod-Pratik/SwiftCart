<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->vendor === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'business_address' => ['required', 'string', 'min:5', 'max:500'],
            'business_type' => ['required', 'string', 'in:individual,registered business,registered_business'],
            'description' => ['nullable', 'string', 'max:1000'],
            'business_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_identification_number' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'verification_document' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'store_name.required' => 'Store name is required.',
            'store_name.min' => 'Store name must be at least 2 characters.',
            'store_name.max' => 'Store name cannot exceed 100 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'business_address.required' => 'Business address is required.',
            'business_address.min' => 'Business address must be at least 5 characters.',
            'business_address.max' => 'Business address cannot exceed 500 characters.',
            'business_type.required' => 'Business type is required.',
            'business_type.in' => 'Please select a valid business type (individual or registered business).',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg, webp.',
            'logo.max' => 'The logo size must not exceed 2MB.',
            'verification_document.file' => 'The verification document must be a file.',
            'verification_document.mimes' => 'The verification document must be a file of type: pdf, jpeg, png, jpg.',
            'verification_document.max' => 'The verification document size must not exceed 5MB.',
        ];
    }
}
