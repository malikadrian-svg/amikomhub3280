<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Event::class);
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:50'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'location'    => ['required', 'string', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB Max
            // In draft mode, they don't have to specify tickets yet.
        ];
    }
}
