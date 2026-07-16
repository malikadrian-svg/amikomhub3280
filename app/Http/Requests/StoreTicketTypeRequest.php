<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = Event::findOrFail($this->route('event'));
        return $this->user()->can('update', $event);
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'price'            => ['required', 'numeric', 'min:0'],
            'quantity_total'   => ['required', 'integer', 'min:1'],
            'start_sale_date'  => ['nullable', 'date'],
            'end_sale_date'    => ['nullable', 'date', 'after_or_equal:start_sale_date'],
            'max_per_order'    => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }
}
