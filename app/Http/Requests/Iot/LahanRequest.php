<?php

namespace App\Http\Requests\Iot;

use Illuminate\Foundation\Http\FormRequest;

class LahanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama_lahan' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'nama_lahan.required' => 'Nama lahan wajib diisi.',
        ];
    }
}
