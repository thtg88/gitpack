<?php

namespace App\Http\Requests;

class DateFilterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'start' => 'nullable|string|date_format:Y-m-d',
            'end' => 'nullable|string|date_format:Y-m-d',
        ];
    }
}
