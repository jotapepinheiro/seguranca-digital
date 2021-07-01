<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
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
            'name' => 'string|max:255',
            'email' => 'email:filter|max:255|unique:users',
            'password' => 'confirmed|min:6',
            'roles' => 'array'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome não pode ser numérico.',
            'name.max' => 'O nome não pode ter mais que :max caracteres.',
            'email.email' => 'Por favor digite um e-mail válido.',
            'email.unique' => 'O e-mail já foi cadastrado.',
            'email.max' => 'O e-mail não pode ter mais que :max caracteres.',
            'password.min' => 'A senha deve ter no mímino :min caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'roles.array' => 'O campo perfil deve ser um array.'
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                "success" => false,
                "code" => 422,
                "error" => $validator->errors(),
                "message" => "Um ou mais campos são requiridos."
            ], 422));
    }

}
