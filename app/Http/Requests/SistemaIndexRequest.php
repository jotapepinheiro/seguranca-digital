<?php

namespace App\Http\Requests;

use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SistemaIndexRequest extends FormRequest
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
            'filter.descricao' => 'max:100',
            'filter.sigla' => 'max:10',
            'filter.email' => 'email:filter|max:100',
            'filter.url' => 'max:50',
            'filter.status' => 'max:50',
            'filter.controles->user->email' => 'email:filter|max:100',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */

    public function messages()
    {
        return [
            'filter.descricao.max' => 'A descricao não pode ter mais que :max caracteres.',
            'filter.sigla.max' => 'A sigla não pode ter mais que :max caracteres.',
            'filter.email.email' => 'Por favor digite um e-mail válido do atendente técnico.',
            'filter.email.max' => 'O e-mail não pode ter mais que :max caracteres.',
            'filter.url.max' => 'A url não pode ter mais que :max caracteres.',
            'filter.status.max' => 'O status não pode ter mais que :max caracteres.',
            'filter.controles->user->email.email' => 'Por favor digite um e-mail válido do usuário.',
            'filter.controles->user->email.max' => 'O e-mail não pode ter mais que :max caracteres.',
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
