<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:80'],
            'customer_mobile' => ['required', 'numeric', 'min:10'],
            'customer_email' => ['required', 'string', 'email', 'max:120'],
            'product_id' => ['required', 'exists:App\Models\Product,id']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_name.required' => 'El campo nombre es requerido',
            'customer_name.string' => 'El campo nombre tiene caracteres no permitidos',
            'customer_name.max' => 'El campo nombre supera el maximo permitido :max',

            'customer_mobile.required' => 'El campo Telefono es requerido',
            'customer_mobile.numeric' => 'El Telefono debe ser numerico',
            'customer_mobile.min' => 'El Telefono no cumple con el minino (10 digitos)',

            'customer_email.required' => 'El campo Correo electronico es requerido',
            'customer_email.string' => 'El Correo electronico tiene caracteres no permitidos',
            'customer_email.email' => 'El Correo electronico no tiene un formato valido',
            'customer_email.max' => 'El Correo electronico supera el maximo permitido (120 caracteres))',

            'product_id.required' => 'El producto es requerido',
            'product_id.exists' => 'El producto no existe',
        ];
    }
}
