<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionUpdateRequest extends FormRequest
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
            'name' => 'string|max:255|unique:permissions,name',
            'display_name' => 'string|max:255',
            'description' => 'string|max:255'
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
            'display_name.max' => 'O nome de exibição não pode ter mais que :max caracteres.',
            'description.max' => 'O descrição não pode ter mais que :max caracteres.',
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
