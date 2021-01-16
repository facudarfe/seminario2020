<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dni' => ['required', 'unique:users,dni,' . $this->id, 'min:7', 'numeric'],
            'lu' => ['nullable', 'unique:users,lu,' . $this->id, 'numeric'],
            'name' => ['required', 'max:255'],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'min:8'],
            'email' => ['required', 'unique:users,email,' . $this->id, 'email'],
            'direccion' => ['nullable', 'max:255'],
            'telefono' => ['nullable', 'numeric', 'max:255']
        ];
    }
}
