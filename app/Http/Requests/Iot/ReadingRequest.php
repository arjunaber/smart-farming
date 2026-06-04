<?php

namespace App\Http\Requests\Iot;

use Illuminate\Foundation\Http\FormRequest;

class ReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ph' => 'nullable|numeric|between:0,14',
            'humidity' => 'nullable|integer|between:0,100',
        ];
    }
}
