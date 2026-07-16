<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * The real authorization logic lives in ReviewPolicy::create().
     * This simply confirms the user is authenticated.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'title'  => ['nullable', 'string', 'max:100'],
            'body'   => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    /**
     * Custom human-readable validation messages (Bahasa Indonesia).
     */
    public function messages(): array
    {
        return [
            'rating.required' => 'Silakan pilih rating bintang untuk event ini.',
            'rating.between'  => 'Rating harus antara 1 sampai 5 bintang.',
            'body.required'   => 'Ulasan tidak boleh kosong.',
            'body.min'        => 'Ulasan minimal harus :min karakter.',
            'body.max'        => 'Ulasan tidak boleh lebih dari :max karakter.',
            'title.max'       => 'Judul ulasan tidak boleh lebih dari :max karakter.',
        ];
    }
}
