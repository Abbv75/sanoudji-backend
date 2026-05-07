<?php

namespace App\Http\Requests\MetadataAttribute;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMetadataAttributeRequest extends FormRequest
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
        $attributeId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255|unique:metadata_attributes,name,' . $attributeId,
            'dataType' => 'sometimes|string|in:text,number,date,boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Cet attribut existe déjà.',
            'dataType.in' => 'Le type doit être : text, number, date ou boolean.',
        ];
    }
}
