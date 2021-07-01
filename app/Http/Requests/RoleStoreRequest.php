<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'permissions' => 'required|array'
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
            'name.unique' => 'O nome já foi cadastrado.',
            'display_name.required' => 'O campo nome de exibição é obrigatório.',
            'display_name.max' => 'O nome de exibição não pode ter mais que :max caracteres.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.max' => 'O descrição não pode ter mais que :max caracteres.',
            'permissions.required' => 'O campo permissões é obrigatório.',
            'permissions.array' => 'O campo permissões deve ser um array.'
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
