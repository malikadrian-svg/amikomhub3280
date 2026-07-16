<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizerRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authentication handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'description'   => ['required', 'string', 'min:50', 'max:1000'],
            'email'         => ['required', 'email', 'max:255', 'unique:organizations,email'],
            'phone'         => ['required', 'string', 'max:20'],
            'website'       => ['nullable', 'url', 'max:255'],
            'address'       => ['required', 'string', 'max:500'],
            'ktp_document'  => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            'legal_document'=> ['nullable', 'file', 'mimes:pdf', 'max:10240'], // 10MB max, PDF only for legal
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique'           => 'Nama organisasi sudah terdaftar.',
            'email.unique'          => 'Email organisasi sudah digunakan.',
            'ktp_document.required' => 'Dokumen KTP wajib diunggah.',
            'ktp_document.mimes'    => 'KTP harus berupa PDF atau gambar (JPG, PNG).',
            'ktp_document.max'      => 'Ukuran KTP maksimal 5MB.',
            'legal_document.mimes'  => 'Dokumen legalitas harus berupa PDF.',
            'legal_document.max'    => 'Ukuran dokumen legalitas maksimal 10MB.',
        ];
    }
}
