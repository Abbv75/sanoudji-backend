<?php

namespace App\Http\Requests\MetadataAttribute;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMetadataAttributeRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:metadata_attributes,name',
            'dataType' => 'required|string|in:text,number,date,boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'attribut est requis.',
            'name.unique' => 'Cet attribut existe déjà.',
            'dataType.required' => 'Le type de l\'attribut est requis.',
            'dataType.in' => 'Le type doit être : text, number, date ou boolean.',
        ];
    }
}
