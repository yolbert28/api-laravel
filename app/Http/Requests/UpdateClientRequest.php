<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->getMethod();

        if ($method == "PUT") {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:clients'],
                'phone' => ['sometimes', 'string', 'max:255'],
                'address' => ['sometimes', 'string', 'max:255'],
            ];
        }else{
            return [
                'name' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'email', 'max:255', 'unique:clients'],
                'phone' => ['sometimes', 'string', 'max:255'],
                'address' => ['sometimes', 'string', 'max:255'],
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener como maximo 255 caracteres',

            'email.required' => 'El correo electronico es requerido',
            'email.email' => 'El correo electronico debe tener una estructura valida',
            'email.unique' => 'El correo electronico ya esta siendo utilizado',
            'email.max' => 'El correo electronico debe tener como maximo 255 caracteres',

            'phone.string' => 'El telefono debe ser una cadena de texto',
            'phone.max' => 'El telefono debe tener como maximo 255 caracteres',

            'address.string' => 'La direccion debe ser una cadena de texto',
            'address.max' => 'La direccion debe tener como maximo 255 caracteres',
        ];
    }
}
