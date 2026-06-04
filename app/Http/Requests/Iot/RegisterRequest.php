<?php

namespace App\Http\Requests\Iot;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_uid' => 'required|string|max:64',
            'device_name' => 'nullable|string|max:150',
        ];
    }
}
