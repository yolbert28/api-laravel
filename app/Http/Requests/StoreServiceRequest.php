<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', 'unique:services'],
            'description' => ['sometimes', 'string', 'max:500'],
            'price' => ['required', 'decimal:2'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'name.unique' => 'El nombre ya esta siendo utilizado',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener como maximo 255 caracteres',

            'price.required' => 'El correo electronico es requerido',
            'price.decimal' => 'El precio debe ser un numero con dos decimales',

            'description.string' => 'La descripcion debe ser una cadena de texto',
            'description.max' => 'La descripcion debe tener como maximo 500 caracteres',
        ];
    }
}
